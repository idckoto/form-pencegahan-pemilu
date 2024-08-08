<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tkp extends Model
{
    use HasFactory;
    protected $table = 'tkp';

    public function twp()
    {
        return $this->hasMany(Twp::class, 'kp_id', 'id');
    }

}
