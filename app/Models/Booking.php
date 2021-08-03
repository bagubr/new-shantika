<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'layout_chair_id', 
        'user_id', 
        'expired_at',
        'booking_at',
        'code_booking'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function layout_chair()
    {
        return $this->belongsTo(LayoutChair::class, 'layout_chair_id');
    }

    public function chair()
    {
        return $this->belongsTo(LayoutChair::class, 'layout_chair_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
