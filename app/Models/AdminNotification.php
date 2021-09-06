<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications';

    protected $fillable = [
        'admin_id', 'reference_id', 'type', 'title', 'body', 'is_seen'
    ];

    //Column `type` follows conventions in model `Notification`

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public static function build(string|array $title, ?string $body, ?string $type, int|string $reference_id = null) {
        if(is_array($title)) {
            return new self($title);
        }
        return new self([
            'title'=>$title,
            'body'=>$body,
            'type'=>$type,
            'reference_id'=>$reference_id,
        ]);
    }    
}
