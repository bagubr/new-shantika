<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAgent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users_agents';

    protected $fillable = [
        'user_id', 'agency_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function agent()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }
}
