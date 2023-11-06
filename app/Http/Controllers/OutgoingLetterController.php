<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OutgoingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = OutgoingLetter::where('from', auth()->user()->email)->get();
            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                    <center>
                        <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                            href="#">
                            Show Detail
                        </a></center>';
                })

                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.dashboard.outgoing.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.outgoing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('type');
        $akademik = AppConfig::where('position', 'AKADEMIK')->with('users')->first();
        $referenceNumber = $this->getReferenceNumber($type);
        $user = auth()->user();
        DB::beginTransaction();

        try {
            $outgoing = OutgoingLetter::create([
                'reference_number' => $referenceNumber,
                'subject' => $request->subject,
                'from' => $user->email,
                'to' => $akademik->users->email,
                'note' => $request->note,
                'type' => $request->type,
                'submit_date' => now(),
                'identifier' => auth()->user()->identifier,
                'status' => 'SENT',
            ]);

            if ($request->hasFile('file_path')) {
                $outgoing->file_path = $request->file_path->store('file', 'public');
            }
            $outgoing->save();

            IncomingLetter::create([
                'reference_number' => $referenceNumber,
                'subject' => $request->subject,
                'from' => $user->email,
                'to' => $akademik->users->email,
                'note' => $request->note,
                'type' => $request->type,
                'submit_date' => now(),
                'status' => 'SENT'
            ]);

            DB::commit();

            return redirect()->route('outgoing.index')->with('success', 'Surat berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan surat: ' . $e->getMessage());
        }
    }




    public function getReferenceNumber($type)
    {
        $currentYear = date('Y');
        $lastSurat = OutgoingLetter::where('type', $type)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->first();

        if (!$lastSurat) {
            $lastId = 1;
        } else {
            $lastId = $lastSurat->id + 1;
        }

        $typeAbbreviation = '';
        $words = explode(' ', $type);
        foreach ($words as $word) {
            $typeAbbreviation .= strtoupper(substr($word, 0, 1));
        }
        $month = date('m');
        $referenceNumber = "STMI-$month-$typeAbbreviation-$currentYear-$lastId";

        return $referenceNumber;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
