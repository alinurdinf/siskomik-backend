<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKrs extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_krs',
        'prodi',
        'semester',
        'total_sks',
        'tahun_akademik',
        'status',
        'created_by',
        'updated_by'
    ];
}
