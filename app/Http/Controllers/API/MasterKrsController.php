<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\MasterKrs;
use App\Models\MasterKrsItem;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKrsController extends Controller
{

    public function fetch()
    {
        $data = MasterKrs::with(['items', 'matakuliah'])->get();
        return ResponseFormatter::success($data, 'Data KRS Berhasil Diambil');
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $krs = MasterKrs::create([
                'kode_krs' => $request->kode_krs,
                'prodi' => $request->prodi,
                'semester' => $request->semester,
                'total_sks' => $request->total_sks ?? 0,
                'tahun_akademik' => $request->tahun_akademik,
                'status' => $request->status,
                'created_by' => auth()->user()->id
            ]);
            DB::commit();

            return ResponseFormatter::success($krs, 'KRS Behasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Terjadi Kesalahan');
        }
    }

    public function store_krs_item(Request $request)
    {
        try {
            DB::beginTransaction();

            $krsItem = MasterKrsItem::create([
                'kode_krs' => $request->kode_krs,
                'kode_matakuliah' => $request->kode_matakuliah,
            ]);

            DB::commit();
            return ResponseFormatter::success($krsItem, $request->kode_matakuliah . 'Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Terjadi Kesalahan');
        }
    }
}
