<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'code', 'user_id', 'start_at', 'end_at', 'quota', 'is_public', 'is_scheduless', 'is_quotaless'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo_histories()
    {
        return $this->hasMany(PromoHistory::class);
    }
}
