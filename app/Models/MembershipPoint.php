<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_id',
        'value',
        'status'
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'id', 'membership_id');
    }
}
