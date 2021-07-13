<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutChair extends Model
{
    use HasFactory;

    protected $table = 'layout_chairs';

    protected $fillable = [
        'name', 'index', 'layout_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'layout_id'
    ];
}
