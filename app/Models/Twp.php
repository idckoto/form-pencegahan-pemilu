<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Twp extends Model
{
    use HasFactory;
    protected $table = 'twp';

    // Definisikan relasi dengan User
    public function kp()
    {
        return $this->belongsTo(Tkp::class, 'kp_id', 'id');
    }

    public function propinsi(){
        return $this->belongsTo(Provinsi::class, 'kdpro', 'id');
    }

}
