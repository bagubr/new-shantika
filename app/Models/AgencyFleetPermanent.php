<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyFleetPermanent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'fleet_id'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'id');
    }

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id', 'id');
    }
}
