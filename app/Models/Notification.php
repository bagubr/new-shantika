<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reference_id', 'title', 'body', 'type', 'is_seen', 'is_saved'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
