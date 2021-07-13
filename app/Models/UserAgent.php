<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgent extends Model
{
    use HasFactory;

    protected $table = 'users_agents';

    protected $fillable = [
        'user_id', 'agency_id'
    ];
}
