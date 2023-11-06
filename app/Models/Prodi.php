<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users()
    {
        return $this->hasOne(User::class, 'identifier', 'kprodi');
    }
}
