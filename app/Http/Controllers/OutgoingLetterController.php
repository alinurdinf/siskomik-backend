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
                            href="' . route('outgoing.show', $item->reference_number) . '">
                            Show Detail
                        </a></center>';
                })
                ->addColumn('status', function ($item) {
                    return '<button type="button" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">' . $item->status . '</button>';
                })
                ->rawColumns(['action', 'status'])
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

    public function showpdf($reference_number)
    {
        $document = OutgoingLetter::where('reference_number', $reference_number)->first();
        $path = $document->file_path;
        $content = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        $url = url($path) . '#toolbar=0';
        return response()->make($content, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($ref_number)
    {
        $data = OutgoingLetter::where('reference_number', $ref_number)->first();
        return view('pages.dashboard.outgoing.show', compact('data'));
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
