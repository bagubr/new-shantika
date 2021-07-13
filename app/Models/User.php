<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'email',
        'avatar',
        'password',
        'token',
        'fcm_token',
        'birth',
        'uid'
    ];

    protected $hidden = [
        'password',
        'token',
        'fcm_token'
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}