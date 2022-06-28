<?php

namespace App\Jobs;

<<<<<<< HEAD
use App\Events\SendingNotification;
use App\Models\Notification;
=======
use App\Events\ExpiredNotificationEvent;
use App\Events\SendingNotification;
use App\Models\Article;
use App\Models\Notification;
use App\Models\Order;
>>>>>>> rilisv1
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Log;
>>>>>>> rilisv1

class PaymentLastThirtyMinuteReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Notification $notification,
        public string|array|null $fcm_token,
<<<<<<< HEAD
        public bool $is_saved
=======
        public bool $is_saved,
        public $order_id
>>>>>>> rilisv1
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
<<<<<<< HEAD
        SendingNotification::dispatch($this->notification, $this->fcm_token, false);
=======
        $order = Order::find($this->order_id);
        if($order->status == Order::STATUS1){
            ExpiredNotificationEvent::dispatch($this->notification, $this->fcm_token, false, [], $this->order_id);
        }
>>>>>>> rilisv1
    }
}
