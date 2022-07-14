<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id', 
        'layout_chair_id',
        'name', 
        'phone', 
        'email', 
        'is_feed', 
        'is_travel', 
        'is_member',
        'note',
    ];

    protected $appends = [
        'chair_name'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function chair()
    {
        return $this->belongsTo(LayoutChair::class, 'layout_chair_id');
    }

    public function getChairNameAttribute()
    {
        return $this->chair()->first()->name;
    }
}
