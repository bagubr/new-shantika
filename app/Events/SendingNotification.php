<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendingNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public string|array $fcm_token;
    public bool $is_saved;
    public $data;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, string|array $fcm_token, bool $is_saved, $data = null)
    {
        $this->notification = $notification;
        $this->fcm_token = $fcm_token;
        $this->is_saved = $is_saved;
        $this->data = $data;
    }
}
