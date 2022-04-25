<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyRoutePermanent extends Model
{
    use HasFactory;
    protected $fillable = [
        'agency_id', 'route_id'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
