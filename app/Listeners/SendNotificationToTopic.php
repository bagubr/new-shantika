<?php

namespace App\Listeners;

use App\Events\SendingNotificationToTopic;
use App\Utils\Firebase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNotificationToTopic
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
    public function handle(SendingNotificationToTopic $event)
    {
        $firebase = Firebase::sendToTopic([
            'title'=>$event->notification->title,
            'body'=>$event->notification->body,
            'type'=>$event->notification->type,
            'reference_id'=>$event->notification->reference_id
        ], $event->topic);

        if($event->is_saved) {
            $event->notification->save();
        }
    }
}
