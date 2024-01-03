<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatakuliahController extends Controller
{
    public function fetch()
    {
        $data = Matakuliah::with('getDosen')->get();
        return ResponseFormatter::success($data, 'Data Matakuliah Berhasil Diambil');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $kode_matakuliah = $request->nama_matakuliah . '-' . $request->kelas . '-' . $request->tahun_akademik . '-' . $request->semester . '-' . (Matakuliah::max('id') + 1);
            $matakuliah = Matakuliah::create([
                'nama_matakuliah' => $request->nama_matakuliah,
                'kode_matakuliah' => $kode_matakuliah,
                'sks' => $request->sks,
                'semester' => $request->semester,
                'dosen_id' => $request->dosen_id,
                'kelas' => $request->kelas,
                'ruang' => $request->ruang,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'tahun_akademik' => $request->tahun_akademik,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return ResponseFormatter::success($matakuliah, 'Matakuliah berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 'Matakuliah gagal ditambahkan', 500);
        }
    }
}
