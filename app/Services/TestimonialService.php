<?php

namespace App\Services;

use App\Events\SendingNotificationToTopic;
use App\Models\Notification;
use App\Models\Testimonial;
use App\Utils\NotificationMessage;

class TestimonialService {
    public static function create($data) {
        if (!empty($data['image'])) {
            $data['image'] = $data['image']->store('testimonial', 'public');
        }

        $testimonial = Testimonial::create($data);

        $payload = NotificationMessage::newTestimonial($testimonial->title, $testimonial->review);
        $notification = new Notification([
            'title'=>$payload[0],
            'body'=>$payload[1],
            'reference_id'=>$testimonial->id,
            'type'=>'TESTIMONIAL',
        ]);
        SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, false);

        return $testimonial;
    }
}
        