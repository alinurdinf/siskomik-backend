<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Krs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $krsMahasiswa = Krs::create([
                'kode_krs' => $request->kode_krs,
                'nim' => $request->nim,
                'is_valid' => false,
                'status' => 'WAITING VALIDATION',
            ]);

            DB::commit();
            return ResponseFormatter::success($krsMahasiswa, 'KRS Berhasil Diambil');
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Terjadi Kesalah');
        }
    }

    public function validate_krs($krs_id)
    {
        try {
            $krs = Krs::find($krs_id)->update(['is_valid' => true, 'valid_by' => auth()->user()->id]);
            DB::commit();

            return ResponseFormatter::success($krs, 'KRS Berhasil di Validasi');
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Terjadi Kesalahan');
        }
    }
}
