<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const TOPIC1 = 'FEATURE';
    const TOPIC2 = 'REMINDER';

    const TYPE1 = 'PAYMENT';
    const TYPE2 = 'ACCOUNT';
    const TYPE3 = 'SLIDER';
    const TYPE4 = 'ARTICLE';
    const TYPE5 = 'CHAIR_CHANGE';

    protected $fillable = [
        'user_id', 'reference_id', 'title', 'body', 'type', 'is_seen'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
