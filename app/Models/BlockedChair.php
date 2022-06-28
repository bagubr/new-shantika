<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedChair extends Model
{
    use HasFactory;

    protected $table = 'blocked_chairs';

    protected $fillable = [
<<<<<<< HEAD
        'fleet_route_id', 'layout_chair_id'
=======
        'fleet_route_id', 'layout_chair_id', 'blocked_date'
>>>>>>> rilisv1
    ];
}
