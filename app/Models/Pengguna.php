<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna_ori';
    protected $primaryKey = 'ID';

    protected $guarded = [];

    // protected $fillable = [
    //     'name',
    //     'username',
    //     'password',
    // ];
}