<?php

namespace App\Jobs;

use App\Events\SendingNotification;
use App\Models\Notification;
use App\Utils\Firebase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingExpiryReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Notification $notification,
        public string|array $fcm_token,
        public bool $is_saved
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SendingNotification::dispatch($this->notification, $this->fcm_token, false);
    }
}
