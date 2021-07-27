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
        'user_agencies'
    ];

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function userAgent()
    {
        return $this->hasMany(UserAgent::class, 'agency_id', 'id');
    }

    public function getUserAgenciesAttribute()
    {
        $user = [];
        return $this->userAgent();
        foreach ($this->userAgent() as $value){
            $user[] = User::find($value->user_id);
        }

        return $user;
    }
}
