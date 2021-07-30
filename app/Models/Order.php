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

    use HasFactory;
    protected $fillable = [
        'user_id',
        'route_id',
        'code_order',
        'status',
        'price',
        'expired_at',
        'exchanged_at',
        'reserve_at',
        'id_member',
        'proof'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agency() {
        return $this->belongsTo(Agency::class, 'user_id', 'user_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function distribution() {
        return $this->hasOne(OrderPriceDistribution::class, 'order_id', 'id');
    }
}
