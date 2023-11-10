<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function outgoings()
    {
        return $this->hasOne(OutgoingLetter::class, 'reference_number', 'reference_number');
    }

    public function mahasiswas()
    {
        return $this->hasOne(Mahasiswa::class, 'email', 'from');
    }

    public function getFilePathAttribute($file_path)
    {
        return config('app.url') . '/storage/' . $file_path;
    }
}
