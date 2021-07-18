<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'time_start', 'time_end'
    ];

    public static function name()
    {
        return self::get()->pluck('name');
    }
}
