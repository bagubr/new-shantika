<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'fleet_id', 'plate_number', 'nickname'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }
}
