<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetHistoryDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset every history in database there is (related to application)';

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
        if(env('APP_DEBUG') === false) {
            return $this->error('You are on production!!');
        }
        DB::beginTransaction();
        User::whereNotNull('id')->update([
            'fcm_token'=>null,
            'token'=>null 
        ]);

        UserToken::whereNotNull('id')->delete();
        Review::whereNotNull('id')->delete();
        Payment::whereNotNull('id')->delete();
        OrderDetail::whereNotNull('id')->delete();
        Order::whereNotNull('id')->delete();
        Booking::whereNotNull('id')->delete();
        $this->info('Application history is gracefully restarted');
        DB::commit();
    }
}
