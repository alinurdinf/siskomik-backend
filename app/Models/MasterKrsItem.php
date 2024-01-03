<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKrsItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_krs',
        'kode_matakuliah'
    ];
}
