<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const TOPIC1 = 'FEATURE';
    const TOPIC2 = 'REMINDER';

    public function __construct(
        public string $title,
        public string $body,
        public string $type
    ) {}

    protected $fillable = [
        'user_id', 'reference_id', 'title', 'body', 'type', 'is_seen'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
