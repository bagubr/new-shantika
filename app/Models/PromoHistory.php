<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'promo_id', 'order_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
