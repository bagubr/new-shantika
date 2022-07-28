<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agencies';

    protected $fillable = [
        'name', 'code', 'city_id', 'lat', 'lng', 'address', 'avatar', 'is_active', 'phone', 'is_agent', 'is_route', 'is_agent_route'
    ];

    protected $appends = [
        'avatar_url',
        'city_name',
        'area_name',
        'morning_time',
        'night_time',
        'time_group',
        'price_agency',
        'price_route',
    ];

    public static function status()
    {
        $status = [
            0 => 'Non Aktif',
            1 => 'Aktif',
        ];
        return $status;
    }

    public function getTimeGroupAttribute()
    {
        $time = TimeClassification::orderBy('id')->get()->map(function ($item) {
            $departure_at = $this->agency_departure_times()?->where('time_classification_id', $item->id)
            ?->orderBy('id')->first()?->departure_at;
            $item2 = $item->name.' '. date('H:i', strtotime($departure_at)) . ' WIB';
            return (!$departure_at)?$item->name.' --:-- WIB':$item2;
        });
        return $time;
    }

    public function getPriceAgencyAttribute()
    {
        return $this->agency_prices()->whereDate('start_at', '<=',date(now()))->orderBy('start_at', 'desc')->orderBy('id', 'desc')->first()?->price??0;
    }

    public function getPriceRouteAttribute()
    {
        return $this->route_prices()->whereDate('start_at', '<=',date(now()))->orderBy('start_at', 'desc')->orderBy('id', 'desc')->first()?->price??0;
    }

    public function prices()
    {
        return $this->hasMany(AgencyPrice::class);
    }

    public function agency_prices()
    {
        return $this->hasMany(AgencyPrice::class);
    }

    public function route_setting()
    {
        return $this->hasMany(RouteSetting::class);
    }

    public function route_prices()
    {
        return $this->hasMany(RoutePrice::class);
    }

    public function agent_departure()
    {
        return $this->hasOne(AgencyDepartureTime::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function agency_departure_times()
    {
        return $this->hasMany(AgencyDepartureTime::class, 'agency_id', 'id');
    }
    public function getMorningTimeAttribute()
    {
        $departure_at = $this->agency_departure_times()?->where('time_classification_id', 1)
            ?->orderBy('id')->first()?->departure_at;
        return 'Pagi ' . date('H:i', strtotime($departure_at)) . ' WIB';
    }
    public function getNightTimeAttribute()
    {
        $departure_at = $this->agency_departure_times()?->where('time_classification_id', 2)?->orderBy('id')->first()?->departure_at;
        return 'Malam ' .  date('H:i', strtotime($departure_at)) . ' WIB';
    }

    public function getCityNameAttribute()
    {
        return $this->city()?->first()?->name;
    }

    public function getAreaNameAttribute()
    {
        return $this->city()?->first()?->area()?->first()?->name;
    }

    public function userAgent()
    {
        return $this->hasMany(UserAgent::class, 'agency_id');
    }

    public function users()
    {
        return $this->hasManyThrough(UserAgent::class,  User::class, 'id', 'user_id', 'id', 'id');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->attributes['avatar'] ? env('STORAGE_URL') . '/' . $this->attributes['avatar'] : "";
    }
    public function deleteAvatar()
    {
        Storage::disk('public')->delete($this->attributes['avatar']);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'departure_agency_id', 'id');
    }
    public function order_details()
    {
        return $this->hasManyThrough(OrderDetail::class, Order::class, 'departure_agency_id', 'order_id', 'id', 'id');
    }

    public function agency_fleet()
    {
        return $this->hasMany(AgencyFleet::class, 'agency_id', 'id');
    }

    public function agency_fleet_permanent()
    {
        return $this->hasMany(AgencyFleetPermanent::class, 'agency_id', 'id');
    }
    public function agency_route()
    {
        return $this->hasMany(AgencyRoute::class, 'agency_id', 'id');
    }

    public function agency_route_permanent()
    {
        return $this->hasMany(AgencyRoutePermanent::class, 'agency_id', 'id');
    }
}
