<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 
        'layout_chair_id', 
        'code_ticket', 
        'name', 
        'phone', 
        'email', 
        'is_feed', 
        'is_travel', 
        'is_member',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function chair()
    {
        return $this->belongsTo(LayoutChair::class, 'layout_chair_id');
    }
}
