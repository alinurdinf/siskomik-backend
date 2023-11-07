<?php

namespace App\Http\Controllers\API;

use App\Models\AppConfig;
use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OutgoingLetterController extends Controller
{
    public function all(Request $request)
    {
        $ref_number = $request->input('reference_number');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');
        if ($ref_number) {
            $outgoing = OutgoingLetter::where('reference_number', $ref_number)->first();

            if ($outgoing)
                return ResponseFormatter::success(
                    $outgoing,
                    'Data surat keluar berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data surat keluar tidak ada',
                    404
                );
        }

        $outgoing = OutgoingLetter::where('identifier', Auth::user()->identifier);

        if ($status)
            $outgoing->where('status', $status);

        return ResponseFormatter::success(
            $outgoing->paginate($limit),
            'Data list surat keluar berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        $akademik = AppConfig::where('position', 'AKADEMIK')->with('users')->first();
        $user = auth()->user();

        $request->validate([
            'reference_number' => 'required|unique:outgoing_letters,reference_number',
            'subject' => 'required|string|max:255',
            'note' => 'required|string',
            'type' => 'required|string',
            'file_path' => 'file|mimes:pdf',
        ]);

        DB::beginTransaction();
        try {
            $outgoing = OutgoingLetter::create([
                'reference_number' => $request->reference_number,
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
                'reference_number' => $request->reference_number,
                'subject' => $request->subject,
                'from' => $user->email,
                'to' => $akademik->users->email,
                'note' => $request->note,
                'type' => $request->type,
                'submit_date' => now(),
                'status' => 'SENT',
            ]);

            DB::commit();
            return ResponseFormatter::success($outgoing, 'Surat berhasil dikirimkan');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($outgoing, $e->getMessage());
        }
    }
}
