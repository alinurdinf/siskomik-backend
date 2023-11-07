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

class IncomingLetterController extends Controller
{
    public function all(Request $request)
    {
        $ref_number = $request->input('reference_number');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');

        if ($ref_number) {
            $incoming = IncomingLetter::where('reference_number', $ref_number)->first();

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
            return ResponseFormatter::success($maildata, 'Data berhasil di validasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($maildata, $e->getMessage());
        }
    }
}
