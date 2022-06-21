<?php

namespace App\Console\Commands;

use App\Jobs\CheckOrderIsExpiredJob;
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
        $order->update([
            'status' => Order::STATUS2
        ]);
        NotificationMessage::OrderExpired($order->reserve_at);
        $user = User::find($order->user_id);
        $notification = new Notification([
            "title"=>'coba',
            "body"=>'',
            "type"=>Notification::TYPE1,
            "reference_id"=>$user->id,
            "user_id"=>$user->id
        ]);
        CheckOrderIsExpiredJob::dispatch($notification, $user->fcm_token,false);
        $this->info('Check successfully!');
    }
}
