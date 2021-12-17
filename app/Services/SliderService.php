<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Events\SendingNotificationToTopic;
use App\Models\Notification;
use App\Models\Slider;
use App\Utils\NotificationMessage;

class SliderService {
    public static function create($data) {
        $data['image'] = $data['image']->store('slider_image', 'public');

        $slider = Slider::create($data);
        self::sendNotification($slider);

        return $slider;
    }

    private static function sendNotification($slider) {
        $badge = NotificationMessage::newSlider($slider->name, $slider->description);
        $notification = Notification::build(
            $badge[0],
            $badge[1],
            $slider->id
        );
        SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, false);
    }
}
        