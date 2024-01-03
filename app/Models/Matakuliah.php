<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;
    protected $guraded = ['id'];

    protected $fillable = [
        'nama_matakuliah',
        'kode_matakuliah',
        'sks',
        'semester',
        'dosen_id',
        'kelas',
        'ruang',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tahun_akademik',
        'status',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function getDosen()
    {
        return $this->hasMany(User::class, 'id', 'dosen_id');
    }
}
