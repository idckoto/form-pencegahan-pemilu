<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    // Regency -> District (One to Many)
    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class);
    }

    // Regency -> Store (One to Many)
    public function formcegah()
    {
        return $this->hasOne(Formcegah::class);
    }
}
