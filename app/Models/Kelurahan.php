<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;
    // Inverse
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    // District -> Store (One to Many)
    public function formcegah()
    {
        return $this->hasOne(Formcegah::class);
    }
}
