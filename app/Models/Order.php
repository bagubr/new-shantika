<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS1 = 'PENDING';
    const STATUS2 = 'EXPIRED';
    const STATUS3 = 'PAID';
    const STATUS4 = 'CANCELED';
    const STATUS5 = 'EXCHANGED';
    const STATUS6 = 'WAITING_CONFIRMATION';
    const STATUS7 = 'DECLINED';
    const STATUS8 = 'FINISHED';

    const STATUS_BOUGHT = [self::STATUS1, self::STATUS3, self::STATUS5, self::STATUS6, self::STATUS8];
    const STATUS_NOT_PAID_CUST = [self::STATUS1];
    const STATUS_WAITING_CUST = [self::STATUS6];
    const STATUS_PAID_CUST = [self::STATUS3, self::STATUS5, self::STATUS8];

    use HasFactory;
    protected $fillable = [
        'user_id',
        'fleet_route_id',
        'time_classification_id',
        'code_order',
        'status',
        'price',
        'expired_at',
        'exchanged_at',
        'reserve_at',
        'id_member',
        'proof',
        'destination_agency_id',
        'departure_agency_id',
        'cancelation_reason',
        'nominal_discount',
        'promo_id',
        'note'
    ];
    protected $appends = [
        'area_name',
    ];
    public static function status()
    {
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED', 'WAITING_CONFIRMATION', 'DECLINED', 'FINISHED'];
        return $status;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'departure_agency_id');
    }

    public function agency_destiny()
    {
        return $this->belongsTo(Agency::class, 'destination_agency_id');
    }

    public function fleet_route()
    {
        return $this->belongsTo(FleetRoute::class, 'fleet_route_id', 'id');
    }

    public function fleet_route_with_trash()
    {
        return $this->belongsTo(FleetRoute::class, 'fleet_route_id', 'id')->withTrashed();
    }

    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function distribution()
    {
        return $this->hasOne(OrderPriceDistribution::class, 'order_id', 'id');
    }

    public function time_classification()
    {
        return $this->belongsTo(TimeClassification::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'id');
    }
    
    public function getAreaNameAttribute()
    {
        return $this->fleet_route?->route?->departure_city?->area?->id;
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }
}
