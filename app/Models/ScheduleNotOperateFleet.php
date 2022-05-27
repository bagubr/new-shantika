<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleNotOperateFleet extends Model
{
    use HasFactory;

    protected $table = "schedule_not_operate_fleets";

    protected $fillable = [
        'fleet_id', 'note', 'schedule_at'
    ];

    public function fleet() {
        return $this->belongsTo(Fleet::class, 'fleet_id', 'id');
    }
}