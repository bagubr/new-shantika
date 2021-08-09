<?php

namespace App\Listeners;

use App\Events\SendingNotification;
use App\Utils\Firebase;
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
        $firebase = Firebase::sendNotification([
            'title'=>$event->notification->title,
            'body'=>$event->notification->body
        ], $event->fcm_token, $event->data);


        if($event->is_saved) {
            $event->notification->save();
        }
    }
}
