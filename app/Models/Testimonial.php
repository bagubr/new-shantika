<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'title', 'review', 'rating', 'image'
    ];

    protected $appends = [
        'name_customer'
    ];

    public function getNameCustomerAttribute()
    {
        return $this->user()->first()?->name;
    }
    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image']);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
