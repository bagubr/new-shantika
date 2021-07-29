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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
}
