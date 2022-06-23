<?php

namespace App\Console\Commands;

use App\Jobs\PaymentLastThirtyMinuteReminderJob;
use App\Models\Notification;
use App\Models\Order;
use App\Services\OrderService;
use App\Utils\NotificationMessage;
use Illuminate\Console\Command;

class NotifyExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Successfully Send Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order = Order::whereStatus(Order::STATUS1)->where('expired_at', '>', date('Y-m-d H:i:s'))->each(function ($item)
        {
            $payload = NotificationMessage::paymentWillExpired();
            $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $item->id);
            PaymentLastThirtyMinuteReminderJob::dispatch($notification, $item->user?->fcm_token, false, $item->id);   
        });
        $this->info('Notify successfully!');
    }
}
