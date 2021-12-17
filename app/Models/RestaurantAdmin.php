<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantAdmin extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'restaurant_id', 'phone'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
