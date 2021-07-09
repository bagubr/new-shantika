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

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->image);
    }
}
