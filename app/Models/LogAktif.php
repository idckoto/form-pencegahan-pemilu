<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktif extends Model
{
    protected $table = 'log_aktifitas';
    protected $fillable = [

        'username',
        'kegiatan',
        'provinsi',
        'kabupaten',
        'kecamatan'
    ];
}
