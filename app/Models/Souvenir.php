<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function souvenir_redeem()
    {
        return $this->hasMany(SouvenirRedeem::class, 'souvenir_id', 'id');
    }
}