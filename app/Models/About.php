<?php

namespace App\Models;

use App\Utils\StorageParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class About extends Model
{
    use HasFactory, StorageParser;

    protected $table = 'abouts';

    protected $fillable = [
        'image', 'description', 'address'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getImageAttribute()
    {
        return $this->appendPath($this->attributes['image']);
    }
    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image']);
    }
}
