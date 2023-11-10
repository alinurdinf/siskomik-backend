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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ValidationRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\TestStatus\Incomplete;

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
                            href="' . route('incoming.show', base64_encode($item->reference_number)) . '">
                            Show Detail
                        </a></center>';
                })
                ->addColumn('status', function ($item) {
                    return '<a href="#" class="font-semibold text-gray-900 underline dark:text-white decoration-blue-500">' . $item->status . '<a>';
                })
                ->rawColumns(['action', 'status', 'reference_number'])
                ->make();
        }
        return view('pages.dashboard.incoming.index');
    }

    public function show($ref_number)
    {
        $ref_number = base64_decode($ref_number);

        $data = IncomingLetter::with(['mahasiswas', 'outgoings'])->where('reference_number', $ref_number)->where('to', auth()->user()->email)->first();
        return view('pages.dashboard.incoming.show', compact('data'));
    }
    public function validation(ValidationRequest $validationRequest)
    {
        DB::beginTransaction();
        $status = getStatus($validationRequest->status);
        try {
            $outgoingData = OutgoingLetter::where('reference_number', $validationRequest->ref_number)->first();
            $incomingData = IncomingLetter::where('reference_number', $validationRequest->ref_number)->first();
            $mahasiswaData = Mahasiswa::where('identifier', $outgoingData->identifier)->first();
            $outgoingData->status = $status;
            $incomingData->status = $status;
            $incomingData->is_validated = true;
            $incomingData->is_read = true;
            $outgoingData->save();
            $incomingData->save();

            // send email to mahasiswa
            DB::commit();
            Notification::send(Auth::user(), new SendPushNotification('Validated', 'Pemintaan telah divalidasi,dan segera diproses'));
            return redirect()->back()->banner('Permintaan berhasil divalidasi');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->route('incoming.index')->dangerBanner('Terjadi kesalah' . $e->getMessage());
        }

        return redirect()->route('incoming.index')->banner('Data berhasil divalidasi dan diteruskan');
    }

    public function showpdf($reference_number)
    {
        $reference_number = base64_decode($reference_number);
        $document = DB::table('incoming_letters')
            ->where('reference_number', $reference_number)
            ->whereNotNull('file_path')
            ->first();
        $path = $document->file_path;
        if ($path) {
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
        $status = getStatus($validationRequest->status);
        try {

            $validator = Validator::make($validationRequest->all(), [
                'file_path' => 'required|file|mimes:pdf',
            ]);

            $outgoingData = OutgoingLetter::where('reference_number', $validationRequest->ref_number)->first();
            $outgoingData->status = $status;
            $outgoingData->reference_number = $validationRequest->no_surat;
            $outgoingData->is_approve = true;
            $outgoingData->save();

            $allIncomingData = IncomingLetter::where('reference_number', $validationRequest->ref_number)->get();

            foreach ($allIncomingData as $incomingData) {
                if ($validationRequest->hasFile('file_path')) {
                    $incomingData->file_path = $validationRequest->file_path->store('file', 'public');
                }
                $incomingData->status = $status;
                $incomingData->is_validated = true;
                $incomingData->reference_number = $validationRequest->no_surat;
                $incomingData->save();
            }

            IncomingLetter::create([
                'reference_number' => $validationRequest->no_surat,
                'subject' => $outgoingData->subject,
                'from' => auth()->user()->email,
                'to' => $outgoingData->from,
                'note' => $validationRequest->note,
                'type' => $outgoingData->type,
                'submit_date' => $outgoingData->submit_date,
                'status' => $status,
                'is_validated' => true,
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('incoming.index')->banner('Balasan berhasil dikirimkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('incoming.index')->dangerBanner('Terjadi kesalahan' . $e->getMessage());
        }
    }
}
