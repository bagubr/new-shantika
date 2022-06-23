<?php

namespace App\Console\Commands;

use App\Jobs\CheckOrderIsExpiredJob;
use App\Jobs\PaymentExpiredReminderJob;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Utils\NotificationMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Successfully Change data';

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
        $order = Order::whereDate('expired_at', '<', date('Y-m-d H:i:s'))->whereIn('status', [Order::STATUS1, Order::STATUS6])->first();
        if($order){
            $order->update([
                'status' => Order::STATUS2
            ]);
            $order->refresh();
            $payload = NotificationMessage::paymentExpired(date("d-M-Y", strtotime($order->reserve_at)));
            $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $order->id);
            PaymentExpiredReminderJob::dispatch($notification, $order?->user?->fcm_token, false, $order->id);
        }
        $this->info('Check successfully!');
    }
}
