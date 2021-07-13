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

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
