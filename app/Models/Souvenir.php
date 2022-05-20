<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Souvenir extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_name',
        'description',
        'point',
        'quantity'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function souvenir_redeem()
    {
        return $this->hasMany(SouvenirRedeem::class, 'souvenir_id', 'id');
    }

    public function getImageNameAttribute($value)
    {
        return url('storage/' . $value);
    }

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image_name']);
    }
}