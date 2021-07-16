<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'review', 'rating', 'image'
    ];

    protected $appends = [
        'name_customer'
    ];

    public function getNameCustomerAttribute()
    {
        return $this->user()->first()?->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
