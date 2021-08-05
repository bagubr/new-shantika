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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function agent()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'user_id');
    }
}
