<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const STATUS1 = 'PENDING';
    const STATUS2 = 'EXPIRED';
    const STATUS3 = 'PAID';
    const STATUS4 = 'CANCELED';
    const STATUS5 = 'EXCHANGED';
    const STATUS6 = 'WAITING_CONFIRMATION';
    const STATUS7 = 'DECLINED';

    protected $fillable = [
        'payment_type_id',
        'order_id',
        'secret_key',
        'status',
        'expired_at',
        'proof',
        'proof_decline_reason'
    ];

    public function getProofAttribute() {
        return env('STORAGE_URL').'/'.$this->attributes['proof'];
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
