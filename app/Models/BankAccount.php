<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BankAccount extends Model
{
    use HasFactory;
    protected $fillable = ['account_name', 'account_number', 'bank_name', 'image'];

    protected $appends = [
        'image_url'
    ];

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image']);
    }

    public function getImageUrlAttribute()
    {
        return env('STORAGE_URL') . '/' . $this->attributes['image'];
    }
}
