<?php

namespace App\Jobs\Admin;

use App\Events\SendingNotificationToAdmin;
use App\Models\AdminNotification;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public AdminNotification|Notification $notification,
        public string|array|null $fcm_token,
        public bool $is_saved
    ) {
        if($this->notification instanceof Notification) {
            $this->notification = AdminNotification::build($this->notification->toArray());
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SendingNotificationToAdmin::dispatch($this->notification, $this->fcm_token, $this->is_saved);
    }
}
