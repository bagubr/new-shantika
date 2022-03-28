<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['member', 'travel', 'booking_expired_duration', 'commision', 'default_food_price', 'xendit_charge', 'time_expired', 'point_purchase'];
}