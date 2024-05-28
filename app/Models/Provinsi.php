<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class);
    }

    // Province -> Store (One to Many)
    public function formcegah()
    {
        return $this->hasOne(Formcegah::class);
    }
}
