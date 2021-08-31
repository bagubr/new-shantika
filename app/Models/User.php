<?php

namespace App\Models;

use App\Utils\StorageParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, StorageParser, SoftDeletes;

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

    protected $appends = [
        'avatar_url',
        'name_agent',
    ];

    public function getAvatarUrlAttribute()
    {
        return url('storage/' . $this->avatar);
    }

    public function deleteAvatar()
    {
        Storage::disk('public')->delete($this->attributes['avatar']);
    }
    public function agencies()
    {
        return $this->belongsTo(UserAgent::class, 'id', 'user_id');
    }
    public function membership()
    {
        return $this->belongsTo(Membership::class, 'id', 'user_id');
    }

    public function token()
    {
        return $this->hasMany(UserToken::class, 'user_id', 'id');
    }

    public function getNameAgentAttribute()
    {
        return $this->name . ' (' . $this->agencies?->agent?->name . ')' ?? '';
    }
}
