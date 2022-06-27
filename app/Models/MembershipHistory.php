<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'agency_id', 'code_order', 'order_id'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }

    public function membership()
    {
        return $this->hasOne(Membership::class, 'user_id', 'customer_id');
    }
}
