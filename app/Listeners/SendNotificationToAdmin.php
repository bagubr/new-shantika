<?php

namespace App\Listeners;

use App\Events\SendingNotificationToAdmin;
use App\Models\AdminNotification;
use App\Utils\Firebase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToAdmin
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
    public function handle(SendingNotificationToAdmin $event)
    {
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
        
        if($event->is_saved) {
            AdminNotification::create($event->notification->toArray());
        }
    }
}
