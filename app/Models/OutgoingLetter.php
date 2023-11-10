<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingLetter extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getFilePathAttribute($file_path)
    {
        return config('app.url') . '/storage/' . $file_path;
    }
}
