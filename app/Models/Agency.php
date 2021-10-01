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
        'name', 'code', 'city_id', 'lat', 'lng', 'address', 'avatar', 'is_active', 'phone'
    ];

    protected $appends = [
        'avatar_url',
        'city_name',
        'morning_time',
        'night_time'
    ];

    public static function status()
    {
        $status = [
            0 => 'Non Aktif',
            1 => 'Aktif',
        ];
        return $status;
    }

    public function agent_departure()
    {
        return $this->hasOne(AgencyDepartureTime::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function agency_departure_times()
    {
        return $this->hasMany(AgencyDepartureTime::class, 'agency_id', 'id');
    }
    public function getMorningTimeAttribute()
    {
        return $this->agency_departure_times()?->where('time_classification_id', 1)?->first()?->departure_at;
    }
    public function getNightTimeAttribute()
    {
        return $this->agency_departure_times()?->where('time_classification_id', 2)?->first()?->departure_at;
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
    public function deleteAvatar()
    {
        Storage::disk('public')->delete($this->attributes['avatar']);
    }
    public function orders() {
        return $this->hasMany(Order::class, 'departure_agency_id', 'id');
    }
    public function order_details() {
        return $this->hasManyThrough(OrderDetail::class, Order::class, 'departure_agency_id', 'order_id', 'id', 'id');
    }
}
