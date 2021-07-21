<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_type_id',
        'order_id',
        'secret_key',
        'status',
        'expired_at',
    ];

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
