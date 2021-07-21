<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutChair extends Model
{
    use HasFactory;

    protected $table = 'layout_chairs';

    protected $fillable = [
        'name', 'index', 'layout_id'
    ];

    protected $appends = [
        'is_available',
        'is_booking',
        'is_door',
        'is_toilet',
    ];

    public function getIsAvailableAttribute()
    {
        $space  = json_decode($this->layout()->first()->space_indexes);
        $toilet = json_decode($this->layout()->first()->toilet_indexes);
        $door   = json_decode($this->layout()->first()->door_indexes);
        return (in_array($this->index, $space))? false : true;
    }
    
    public function getIsDoorAttribute()
    {
        $door   = json_decode($this->layout()->first()->door_indexes);
        return (in_array($this->index, $door))? true : false;
    }
    
    public function getIsToiletAttribute()
    {
        $toilet = json_decode($this->layout()->first()->toilet_indexes);
        return (in_array($this->index, $toilet))? true : false;
    }

    public function getIsBookingAttribute()
    {
        // $booking = Booking::where('route_id', $this->layout()->firts()->route_id);
        return false;
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    protected $hidden = [
        'created_at', 'updated_at', 'layout_id'
    ];
}
