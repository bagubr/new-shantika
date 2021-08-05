<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'value', 'type', 'icon', 'link'];

    public function getIconAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function deleteIcon()
    {
        Storage::disk('public')->delete($this->attributes['icon']);
    }
}
