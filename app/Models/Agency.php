<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'name', 'city_id', 'lat', 'lng', 'address', 'avatar'
    ];

    protected $appends = [
        'phone',
        'avatar_url',
        'city_name'
    ];


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
}
