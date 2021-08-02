<?php

namespace App\Listeners;

use App\Events\SendingNotification;
use App\Utils\Firebase;
use App\Utils\NotificationBody;
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
        Log::error('Mengirim notifikasi...');

        $body = new NotificationBody($event->notification->title, $event->notification->body);
        $firebase = Firebase::sendNotification($body, 'eWNTDeSJRji5e6yp7crv20:APA91bENnRIO-ma2akxQKc6jIJPW4rWSI9G82ReheM4dKV-pUwOi3Juem-nrw3Cmo5BjXtc5X6hxWAcG96sn7s1LIA6SF9nRHlzT9cm_vLN4ssceebPSUvc1AIomiJep5F2kQKuVLpt9');

        Log::error($firebase);
    }
}
