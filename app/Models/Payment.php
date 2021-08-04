<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
    const STATUS8 = 'FINISHED';

    protected $fillable = [
        'payment_type_id',
        'order_id',
        'secret_key',
        'status',
        'expired_at',
        'proof',
        'proof_decline_reason',
        'paid_at'
    ];

    protected $appends = [
        'proof_url'
    ];

    public function getProofUrlAttribute()
    {
        return url('storage/' . $this->attributes['proof']);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function deleteProof()
    {
        Storage::disk('public')->delete($this->attributes['proof']);
    }
}
