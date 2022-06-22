<?php

namespace App\Listeners;

use App\Events\ExpiredNotificationEvent;
use App\Models\Notification;
use App\Models\Order;
use App\Utils\Firebase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ExpiredNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ExpiredNotificationEvent $event)
    {
        $order = Order::find($event->notification->reference_id);
        if($order->status == Order::STATUS1){
            if(gettype(json_decode($event->fcm_token)) == 'array') {
                foreach(json_decode($event->fcm_token) as $fcm_token) {
                    Firebase::sendNotification([
                        'title'=>$event->notification->title,
                        'body'=>$event->notification->body,
                    ], $fcm_token, $event->data);
                }
            } else {
                Firebase::sendNotification([
                    'title'=>$event->notification->title,
                    'body'=>$event->notification->body,
                ], $event->fcm_token, $event->data);
            }
            
            if($event->is_saved && !empty($event->notification->user_id)) {
                Notification::create($event->notification->toArray());
            }
        }
    }
}
