<?php

namespace App\Models;

use App\Casts\CodeMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code_member = "{$model->max('code_member')}" + 1;
        });
    }

    protected $fillable = [
        'agency_id', 'code_member', 'user_id', 'sum_point', 'name', 'phone', 'address'
    ];

    protected $appends = [
        'code_member_stk',
        'point'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id' );
    }

    public function membership_point()
    {
        return $this->hasMany(MembershipPoint::class, 'membership_id', 'id');
    }

    public function getCodeMemberStkAttribute()
    {
        return 'SNTK' . sprintf('%08d', $this->code_member);
    }

    public function getPointAttribute()
    {
        return $this->sum_point;
    }

    public function souvenir_redeem()
    {
        return $this->hasMany(SouvenirRedeem::class, 'membership_id', 'id');
    }

}