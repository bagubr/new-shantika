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
use Illuminate\Support\Facades\Log;

class SendingNotificationToTopic
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public string|array $topic;
    public bool $is_saved;
    public $data;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, string $topic, bool $is_saved, $data = null)
    {
        $this->notification = $notification;
        $this->topic = $topic;
        $this->is_saved = $is_saved;
        $this->data = $data;
    }
}
