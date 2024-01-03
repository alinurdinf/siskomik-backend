<?php

namespace App\Http\Controllers\API;

use App\Models\Prodi;
use App\Models\AppConfig;
use App\Models\Mahasiswa;
use App\Mail\LetterApproval;
use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ValidationRequest;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;

class IncomingLetterController extends Controller
{
    public function all(Request $request)
    {
        $ref_number = $request->input('reference_number');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');
        if ($ref_number) {
            $incoming = IncomingLetter::where('reference_number', $ref_number)->with('outgoings')->first();
            if ($incoming)
                return ResponseFormatter::success(
                    $incoming,
                    'Data surat masuk berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data surat masuk tidak ada',
                    404
                );
        }

        $incoming = IncomingLetter::where('to', Auth::user()->email);
        if ($status)
            $incoming->where('status', $status);

        return ResponseFormatter::success(
            $incoming->paginate($limit),
            'Data list surat masuk berhasil diambil'
        );
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
            return ResponseFormatter::success($status, 'Data berhasil di validasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($status, $e->getMessage());
        }
    }

    public function sendReply(Request $validationRequest)
    {
        $status = getStatus($validationRequest->status);
        try {

            $validator = Validator::make($validationRequest->all(), [
                'file_path' => 'required|file|mimes:pdf',
            ]);

            $outgoingData = OutgoingLetter::where('reference_number', $validationRequest->ref_number)->first();
            $outgoingData->status = $status;
            $outgoingData->letter_number = $validationRequest->no_surat;
            $outgoingData->is_approve = true;
            $outgoingData->save();

            $allIncomingData = IncomingLetter::where('reference_number', $validationRequest->ref_number)->get();

            foreach ($allIncomingData as $incomingData) {
                if ($validationRequest->hasFile('file_path')) {
                    $incomingData->file_path = $validationRequest->file_path->store('file', 'public');
                }
                $incomingData->status = $status;
                $incomingData->is_validated = true;
                $incomingData->letter_number = $validationRequest->no_surat;
                $incomingData->save();
            }

            $outgoing = OutgoingLetter::create([
                'reference_number' => 'Reply-' . $validationRequest->ref_number,
                'letter_number' => $validationRequest->no_surat,
                'subject' => 'Jawaban Permintaan Surat',
                'from' => $outgoingData->to,
                'to' => $outgoingData->from,
                'note' => $validationRequest->note,
                'type' => $outgoingData->type,
                'letter_date' => now(),
                'submit_date' => now(),
                'identifier' => auth()->user()->identifier,
                'status' => getStatus('APPROVE'),
            ]);

            if ($validationRequest->hasFile('file_path')) {
                $outgoing->file_path = $validationRequest->file_path->store('file', 'public');
            }

            $outgoing->save();
            $sendedLetter = IncomingLetter::create([
                'reference_number' => $validationRequest->ref_number,
                'letter_number' => $validationRequest->no_surat,
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
            return ResponseFormatter::success($sendedLetter, 'Data berhasil di dikirimkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($sendedLetter, 'Data gagal dikirimkan');
        }
    }
}
