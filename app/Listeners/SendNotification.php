<?php

namespace App\Listeners;

use App\Events\SendingNotification;
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
                $firebase = Firebase::sendNotification([
                    'title'=>$event->notification->title,
                    'body'=>$event->notification->body,
                ], $fcm_token, $event->data);
                Log::info($fcm_token);
            }
        } else {
            $firebase = Firebase::sendNotification([
                'title'=>$event->notification->title,
                'body'=>$event->notification->body,
            ], $event->fcm_token, $event->data);
            Log::info(json_encode($firebase));
        }
        

        if($event->is_saved && !empty($event->notification->user_id)) {
            $event->notification->save();
        }
    }
}
