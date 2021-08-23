<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cities';

    protected $fillable = [
        'name', 'province_id', 'area_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'province_id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    public function agent()
    {
        return $this->hasMany(Agency::class, 'city_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
