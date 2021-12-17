<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SocialMedia extends Model
{
    use HasFactory;

    protected $table = 'social_medias';
    protected $fillable = ['name', 'icon', 'value'];

    public function getIconAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function deleteIcon()
    {
        Storage::disk('public')->delete($this->attributes['icon']);
    }
}
