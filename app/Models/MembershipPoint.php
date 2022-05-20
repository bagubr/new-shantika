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

    protected $appends = [
        'status_operator'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }
}