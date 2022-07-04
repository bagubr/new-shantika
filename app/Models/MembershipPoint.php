<?php

namespace App\Models;

use App\Casts\MembershipPointStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPoint extends Model
{
    use HasFactory;

    const STATUS_CREATE = 'create';
    const STATUS_REDEEM = 'redeem';
    const STATUS_PURCHACE = 'purchase';

    protected $fillable = [
        'membership_id',
        'value',
        'status',
        'message'
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}