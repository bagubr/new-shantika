<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'provinces';

    protected $fillable = [
        'name'
    ];
    public function agencies()
    {
        return $this->hasManyThrough(Agency::class, City::class);
    }
    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'id');
    }
    public function user_agencies()
    {
        return $this->hasManyThrough(UserAgent::class, Agency::class);
    }
}
