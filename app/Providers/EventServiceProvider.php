<?php

namespace App\Providers;
use App\Events\SendingNotification;
use App\Events\SendingNotificationToAdmin;
use App\Events\SendingNotificationToTopic;
use App\Events\ExpiredNotificationEvent;
use App\Listeners\ExpiredNotificationListener;
use App\Listeners\SendNotification;
use App\Listeners\SendNotificationToAdmin;
use App\Listeners\SendNotificationToTopic;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendingNotification::class => [
            SendNotification::class
        ],
        ExpiredNotificationEvent::class => [
            ExpiredNotificationListener::class
        ],
        SendingNotificationToTopic::class => [
            SendNotificationToTopic::class
        ],
        SendingNotificationToAdmin::class => [
            SendNotificationToAdmin::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Event::listen(
        //     SendingNotification::class,
        //     [SendNotification::class, 'handle']
        // );

        // Event::listen(
        //     SendingNotificationToTopic::class,
        //     [SendNotificationToTopic::class, 'handle']
        // );

        // Event::listen(
        //     SendingNotificationToAdmin::class,
        //     [SendNotificationToAdmin::class, 'handle']
        // );
    }
}
