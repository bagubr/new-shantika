<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'name', 'city_id'
    ];

    protected $appends = [
        'address',
        'phone'
    ];

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function userAgent()
    {
        return $this->belongsTo(UserAgent::class, 'id', 'agency_id');
    }

    public function getAddressAttribute()
    {
        return $this->userAgent()->first()->user()->first()->address;
    }

    public function getPhoneAttribute()
    {
        return $this->userAgent()->first()->user()->first()->phone;
    }
}
