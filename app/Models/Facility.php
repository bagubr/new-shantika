<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'name', 'image'
    ];

    public function getImageAttribute() {
        return env('STORAGE_URL').'/'.$this->attributes['image'];
    }
}
