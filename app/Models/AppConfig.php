<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users()
    {
        return $this->hasOne(User::class, 'identifier', 'identifier');
    }
}
