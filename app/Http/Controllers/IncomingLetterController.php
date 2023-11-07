<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\AppConfig;
use App\Models\Mahasiswa;
use App\Mail\LetterApproval;
use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ValidationRequest;
use PHPUnit\Framework\TestStatus\Incomplete;
use Yajra\DataTables\Facades\DataTables;

class IncomingLetterController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = IncomingLetter::where('to', auth()->user()->email)->get();
            return DataTables::of($query)
                ->addColumn('reference_number', function ($item) {
                    if (!$item->is_read) {
                        $badge = '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">New</span>';
                    } else {
                        $badge = '<span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-purple-900 dark:text-purple-300">Opened</span>';
                    }
                    return $item->reference_number . ' ' . $badge;
                })
                ->addColumn('action', function ($item) {
                    return '
                    <center>
                        <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('incoming.show', $item->reference_number) . '">
                            Show Detail
                        </a></center>';
                })
                ->addColumn('status', function ($item) {
                    return '<button type="button" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">' . $item->status . '</button>';
                })
                ->rawColumns(['action', 'status', 'reference_number'])
                ->make();
        }
        return view('pages.dashboard.incoming.index');
    }

    public function show($ref_number)
    {
        $data = IncomingLetter::with('outgoings')->where('reference_number', $ref_number)->where('to', auth()->user()->email)->first();
        return view('pages.dashboard.incoming.show', compact('data'));
    }
    public function validation(ValidationRequest $validationRequest)
    {
        DB::beginTransaction();

        try {
            $outgoingData = OutgoingLetter::where('reference_number', $validationRequest->ref_number)->first();
            $mahasiswaData = Mahasiswa::where('identifier', $outgoingData->identifier)->first();

            if ($validationRequest->approver == 'DIREKTUR') {
                $approvalData = AppConfig::where('position', 'DIREKTUR')->with('users')->first();
            } else {
                $approvalData = Prodi::where('code', $mahasiswaData->prodi)->with('users')->first();
            }
            $email = $approvalData->users->email;
            $outgoingData->status = 'ON-PROCESS';
            $outgoingData->to = $email;
            $outgoingData->is_validated = true;
            $outgoingData->save();

            $incomingData = IncomingLetter::where('reference_number', $validationRequest->ref_number)->where('to', auth()->user()->email)->first();
            $incomingData->status = 'ON-PROCESS';
            $incomingData->is_validated = true;
            $incomingData->is_read = true;
            $incomingData->save();

            IncomingLetter::create([
                'reference_number' => $validationRequest->ref_number,
                'subject' => $outgoingData->subject,
                'from' => $outgoingData->from,
                'to' => $email,
                'note' => $outgoingData->note,
                'type' => $outgoingData->type,
                'submit_date' => $outgoingData->submit_date,
                'status' => 'ON-PROCESS',
                'is_validated' => false
            ]);

            $maildata = DB::table('outgoing_letters')
                ->join('mahasiswas', 'outgoing_letters.identifier', '=', 'mahasiswas.identifier')
                ->select('outgoing_letters.*', 'mahasiswas.*')
                ->where('outgoing_letters.reference_number', $outgoingData->reference_number)
                ->first();
            Mail::to($email)->send(new LetterApproval($maildata));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->route('incoming.index')->dangerBanner('Terjadi kesalah' . $e->getMessage());
        }

        return redirect()->route('incoming.index')->banner('Data berhasil divalidasi dan diteruskan');
    }

    public function showpdf($reference_number)
    {
        $document = IncomingLetter::where('reference_number', $reference_number)
            ->whereNotNull('file_path')
            ->first();
        if ($document) {
            $path = $document->file_path;
            $content = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
            $url = url($path) . '#toolbar=0';
            return response()->make($content, 200, [
                'Content-Type' => 'application/pdf',
            ]);
        } else {
            return view('errors.404');
        }
    }
    public function approvalReply(Request $validationRequest)
    {
        DB::beginTransaction();
        $akademik = AppConfig::where('position', 'AKADEMIK')->with('users')->first();

        try {
            $outgoingData = OutgoingLetter::where('reference_number', $validationRequest->ref_number)->first();
            $outgoingData->status = $validationRequest->status;
            $outgoingData->is_approve = true;
            $outgoingData->save();

            $allIncomingData = IncomingLetter::where('reference_number', $validationRequest->ref_number)->get();
            foreach ($allIncomingData as $incomingData) {
                if ($validationRequest->hasFile('file_path')) {
                    $incomingData->file_path = $validationRequest->file_path->store('file', 'public');
                }
                $incomingData->status = $validationRequest->status;
                $incomingData->is_validated = true;
                $incomingData->save();
            }
            IncomingLetter::where('reference_number', $validationRequest->ref_number)->where('to', auth()->user()->email)->update(['is_read' => true]);
            IncomingLetter::create([
                'reference_number' => $validationRequest->ref_number,
                'subject' => $outgoingData->subject,
                'from' => auth()->user()->email,
                'to' => $akademik->users->email,
                'note' => $validationRequest->note,
                'type' => $outgoingData->type,
                'submit_date' => $outgoingData->submit_date,
                'status' => $validationRequest->status,
                'is_validated' => true
            ]);

            DB::commit();
            return redirect()->route('incoming.index')->banner('Balasan berhasil dikirimkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('incoming.index')->dangerBanner('Terjadi kesalahan' . $e->getMessage());
        }
    }
}
