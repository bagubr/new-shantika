<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FleetRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::addGlobalScope(function (Builder $builder) {
            $query = $builder->getQuery();
            $builder->when(empty($query->columns), fn ($q) => $q->select('*'))
                ->addSelect((function($query) {
                    return DB::raw("(select price from fleet_route_prices where start_at < x and end_at > x  limit 1) as price");
                })($query));
        });
    }

    protected $table = 'fleet_routes';

    protected $fillable = [
        'fleet_detail_id', 'route_id', 'price', 'is_active'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = [
        'price'
    ];

    public function fleet_detail()
    {
        return $this->belongsTo(FleetDetail::class, 'fleet_detail_id', 'id')->withTrashed();
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'fleet_route_id', 'id');
    }

    public function blocked_chairs() {
        return $this->hasMany(BlockedChair::class, 'fleet_route_id', 'id');
    }
}
