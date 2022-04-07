<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Events\SendingNotificationToTopic;
use App\Models\Notification;
use App\Models\Promo;
use App\Utils\NotificationMessage;

class PromoService {

    public static function create($data)
    {
        $data['image'] = $data['image']->store('promo', 'public');
        $promo = Promo::create($data);
        $message = NotificationMessage::promo($promo);

        if(isset($data['is_public']) && $data['is_public']){
            $notification = Notification::build($message[0], $message[1], Notification::TYPE7, $promo->id);
            SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, true, [
                'reference_id'=>(string) $promo->id,
                'type'=>Notification::TYPE7
            ]);
        }else{
            $notification = Notification::build($message[0], $message[1], Notification::TYPE7, $promo->id, $promo->user_id);
            SendingNotification::dispatch($notification, $promo->user->fcm_token, true);
        }
    }
}
        