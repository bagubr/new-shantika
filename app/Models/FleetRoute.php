<?php

namespace App\Models;

use App\Scopes\FleetRoute\PriceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FleetRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        $date = Request::input('date');
        static::addGlobalScope(new PriceScope($date));
    }

    protected $table = 'fleet_routes';

    protected $fillable = [
        'fleet_detail_id', 'route_id', 'is_active'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function fleet_detail()
    {
        return $this->belongsTo(FleetDetail::class, 'fleet_detail_id', 'id')->withTrashed();
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function time_change_route()
    {
        return $this->belongsTo(TimeChangeRoute::class, 'id', 'fleet_route_id')->where('time_classification_id', '!=', $this->fleet_detail()?->first()?->time_classification_id);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'fleet_route_id', 'id');
    }

    public function blocked_chairs() {
        return $this->hasMany(BlockedChair::class, 'fleet_route_id', 'id');
    }

    public function prices() {
        return $this->hasMany(FleetRoutePrice::class);
    }
}
