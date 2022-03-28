<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirRedeem extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'point_used', 'status', 'membership_id', 'souvenir_id'];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'id', 'membership_id');
    }

    public function souvenir()
    {
        return $this->belongsTo(Souvenir::class, 'id', 'souvenir_id');
    }
}
