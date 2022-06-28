<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'address', 'phone', 'image', 'bank_name', 'bank_owner', 'bank_account', 'lat', 'long'];


    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image']);
    }
    public function admins()
    {
        return $this->hasMany(RestaurantAdmin::class, 'restaurant_id', 'id');
    }
<<<<<<< HEAD
=======

    public function admin()
    {
        return $this->belongsToMany(Admin::class, 'restaurant_admins', 'restaurant_id', 'admin_id', 'id', 'id', 'admin')->withPivot('phone');
    }
>>>>>>> rilisv1
    public function food_reddem_histories()
    {
        return $this->hasMany(FoodRedeemHistory::class, 'restaurant_id', 'id');
    }
    public function order_details()
    {
        return $this->hasManyThrough(FoodRedeemHistory::class, OrderDetail::class);
    }
}
