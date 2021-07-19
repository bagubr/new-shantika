<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $table = 'checkpoints';

    protected $fillable = [
        'route_id', 'arrived_at', 'agency_id', 'order'
    ];

    protected $appends = [
        'name_city',
        'name_agent',
    ];

    public function getNameCityAttribute()
    {
        return $this->agency()->first()?->city()->first()?->name;
    }
    
    public function getNameAgentAttribute()
    {
        return $this->agency()->first()?->name;
    }

    public function route() {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function agency() {
        return $this->belongsTo(Agency::class, 'agency_id', 'id');
    }
}
