<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleNotOperate extends Model
{
    use HasFactory;
    protected $fillable = [
        'route_id', 'note', 'schedule_at'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
    
}
