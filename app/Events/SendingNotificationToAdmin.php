<?php

namespace App\Events;

use App\Models\AdminNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendingNotificationToAdmin
{
    use Dispatchable, InteractsWithSockets;

    public AdminNotification $notification;
    public string|array|null $fcm_token;
    public bool $is_saved;
    public array $data;
    
    /**
     * Create a new event instance.
     * 
     *
     * @return void
     */
    public function __construct(AdminNotification $notification, string|array|null $fcm_token, bool $is_saved, array $data = [])
    {
        $this->notification = $notification;
        $this->fcm_token = $fcm_token ?? "";
        $this->is_saved = $is_saved;
        if($data != []) {
            $this->data = $data;
        } else {
            $this->data = [
                'reference_id'=>(string )$notification->reference_id,
                'type'=>$notification->type
            ];
        }
    }
}
