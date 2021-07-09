<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Information extends Model
{
    use HasFactory;

    protected $table = 'informations';
    protected $fillable = ['name', 'address', 'description', 'image'];

    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function deleteImage()
    {
        Storage::delete($this->image);
    }
}
