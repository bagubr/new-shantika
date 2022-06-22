<?php

namespace App\Jobs;

use App\Events\ExpiredNotificationEvent;
use App\Events\SendingNotification;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PaymentExpiredReminderJob implements ShouldQueue
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
        public bool $is_saved,
        public $order_id
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ExpiredNotificationEvent::dispatch($this->notification, $this->fcm_token, false);
    }
}
