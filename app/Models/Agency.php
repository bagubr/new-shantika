<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agencies';

    protected $fillable = [
        'name', 'city_id', 'lat', 'lng', 'address', 'avatar', 'is_active'
    ];

    protected $appends = [
        'phone',
        'avatar_url',
        'city_name'
    ];

    public static function status()
    {
        $status = [
            0 => 'Non Aktif',
            1 => 'Aktif',
        ];
        return $status;
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function getCityNameAttribute()
    {
        return $this->city()?->first()?->name;
    }
    public function userAgent()
    {
        return $this->hasMany(UserAgent::class, 'agency_id');
    }

    public function users()
    {
        return $this->hasManyThrough(UserAgent::class,  User::class, 'id', 'user_id', 'id', 'id');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->attributes['avatar'] ? env('STORAGE_URL') . '/' . $this->attributes['avatar'] : "";
    }

    public function getPhoneAttribute()
    {
        return $this->userAgent()?->first()?->user()?->first()?->phone;
    }

    public function deleteAvatar()
    {
        Storage::disk('public')->delete($this->attributes['avatar']);
    }
}
