<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AppConfigController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = AppConfig::query();
            return DataTables::of($query)
                ->addColumn('action', function ($item) {
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
                ->addColumn('is_active', function ($item) {
                    return $item->is_active ? 'Active' : 'In-Active';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.dashboard.app config.index');
    }
}
