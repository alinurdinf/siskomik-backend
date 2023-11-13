<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Models\Role;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class UserController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            $query = DB::table('users')
                ->select('users.*', 'roles.name AS role_name')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->get();

            return DataTables::of($query)
                ->addColumn('action', function ($user) {
                    return '
                    <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                        href="#">
                        Show Detail
                    </a>
                    <form class="inline-block" action="#" method="POST">
                        <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->addColumn('role', function ($user) {
                    return $user->role_name;
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.dashboard.user.index');
    }

    public function create(){
        return view('pages.dashboard.user.create');
    }

    public function store(Request $request){
        try {
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' =>'',
                'identifier' => 'required',
                'username' => 'required',
                'account' => 'required',
            ])->validate();
    
            if ($request->input('account') == 'MAHASISWA') {
                $role = Role::where('name', 'mahasiswa')->first();
            } else {
                $role = Role::where('name', 'dosen')->first();
            }
    
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'identifier' => $request->input('identifier'),
                'password' => Hash::make($request->input('password')),
            ]);
    
            $user->syncRolesWithoutDetaching([$role]);
    
            return redirect()->back()->banner('User Account berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->dangerBanner('Error: ' . $e->getMessage());
        }
    }
    
}
