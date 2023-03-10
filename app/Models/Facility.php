<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'name', 'image'
    ];
    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function deleteImage()
    {
        Storage::delete($this->attributes['image']);
    }
}
