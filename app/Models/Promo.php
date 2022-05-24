<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Promo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 
        'code', 
        'user_id', 
        'start_at', 
        'end_at', 
        'quota', 
        'is_public', 
        'is_quotaless',
        'description',
        'image',
        'percentage_discount',
        'maximum_discount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo_histories()
    {
        return $this->hasMany(PromoHistory::class);
    }

    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image']);
    }
}
