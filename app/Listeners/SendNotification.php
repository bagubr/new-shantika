<?php

namespace App\Listeners;

use App\Events\SendingNotification;
use App\Models\Notification;
use App\Utils\Firebase;
use Google\Service\AIPlatformNotebooks\Instance;
use Illuminate\Support\Facades\Log;

class SendNotification
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
     * @param  SendingNotification  $event
     * @return void
     */
    public function handle(SendingNotification $event)
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
        
        if($event->is_saved && !empty($event->notification->user_id)) {
            Notification::create($event->notification->toArray());
        }
    }
}
