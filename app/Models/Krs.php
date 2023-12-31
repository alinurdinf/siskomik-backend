<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_krs',
        'nim',
        'is_valid',
        'status',
        'valid_by'
    ];
}
