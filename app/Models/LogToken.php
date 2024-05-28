<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogToken extends Model
{
    protected $fillable = [
        
        'username',
        'token',
        'refresh_token',
    ];
}
