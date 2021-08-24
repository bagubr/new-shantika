<?php

namespace App\Services;

use App\Events\SendingNotificationToTopic;
use App\Models\Notification;
use App\Models\Testimonial;

class TestimonialService {
    public static function create($data) {
        if (!empty($data['image'])) {
            $data['image'] = $data['image']->store('testimonial', 'public');
        }

        $testimonial = Testimonial::create($data);

        $notification = new Notification([
            'title'=>$testimonial->title,
            'body'=>str_pad($testimonial->title, 40),
            'reference_id'=>$testimonial->id,
            'type'=>'TESTIMONIAL',
        ]);
        SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, false);

        return $testimonial;
    }
}
        