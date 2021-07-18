<?php

namespace App\Models;

use App\Utils\StorageParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory, StorageParser;

    protected $table = 'abouts';

    protected $fillable = [
        'image', 'description', 'address'
    ];

    public function getImageAttribute() {
        return $this->appendPath($this->attributes['image']);
    }
}
