<?php

namespace App\Models;

use App\Utils\StorageParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, StorageParser;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'token',
        'fcm_token',
        'birth',
        'birth_place',
        'gender',
        'uuid',
        'address',
    ];

    protected $hidden = [
        'password',
        'token',
        'fcm_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function getAvatarAttribute()
    // {
    //     return $this->appendPath($this->attributes['avatar']);
    // }

    public function getAvatarAttribute($value)
    {
        return url('storage/' . $value);
    }

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['avatar']);
    }
}
