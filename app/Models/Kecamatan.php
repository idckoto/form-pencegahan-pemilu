<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    // Inverse
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    // District -> Village (One to Many)
    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class);
    }

    // District -> Store (One to Many)
    public function formcegah()
    {
        return $this->hasOne(Formcegah::class);
    }
}
