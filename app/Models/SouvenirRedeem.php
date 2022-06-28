<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirRedeem extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = ['quantity', 'point_used', 'status', 'membership_id', 'souvenir_id', 'note'];

=======
    protected $fillable = [
        'quantity', 
        'point_used', 
        'status', 
        'membership_id', 
        'souvenir_id', 
        'note',
        'agency_id'
    ];

    const STATUS_DECLINE = 'DECLINE';
    const STATUS_WAITING = 'WAITING';
    const STATUS_ON_PROCESS = 'ON_PROCESS';
    const STATUS_DELIVERED = 'DELIVERED';
    
>>>>>>> rilisv1
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = [
        'souvenir_name'
    ];

    public function GetSouvenirNameAttribute()
    {
<<<<<<< HEAD
        return $this->souvenir()->first()->name;
=======
        return $this->souvenir()?->first()?->name??'';
>>>>>>> rilisv1
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }

    public function souvenir()
    {
        return $this->belongsTo(Souvenir::class, 'souvenir_id', 'id');
    }
<<<<<<< HEAD
=======

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'id');
    }
>>>>>>> rilisv1
}