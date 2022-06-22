<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpiredNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public string|array|null $fcm_token;
    public bool $is_saved;
    public array $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, string|array|null $fcm_token, bool $is_saved, array $data = [])
    {
        $order = Order::find($notification->reference_id);
        if($order->status == Order::STATUS1){
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
}
