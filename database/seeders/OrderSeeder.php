<?php

namespace Database\Seeders;

use App\Models\FleetRoute;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Route;
use App\Models\User;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $route = FleetRoute::all()->random();
            $date = date('Y-m-d H:i:s');
            $order = Order::create([
                'user_id'           => User::all()->random()->id,
                'fleet_route_id'    => $route->id,
                'code_order'        => 'STK-' . date('YmdHis'),
                // 'status'            => $faker->randomElement($array = array('PENDING', 'EXPIRED', 'PAID', 'CANCELED', 'EXCHANGED')),
                'status'            => 'PAID',
                'price'             => $route->price,
                'expired_at'        => date('Y-m-d H:i:s', strtotime($date . ' +3 day')),
                'reserve_at'        => Carbon::now()->subDays(rand(1, 10)),
            ]);

            $space  = $route->fleet_detail->fleet->layout->space_indexes;
            $toilet = $route->fleet_detail->fleet->layout->toilet_indexes;
            $door   = $route->fleet_detail->fleet->layout->door_indexes;
            OrderDetail::create([
                'order_id'          => $order->id,
                'layout_chair_id'   => $route->fleet_detail->fleet->layout->chairs->whereNotIn('index', $space)
                    ->whereNotIn('index', $toilet)->whereNotIn('index', $door)->random()->id,
                'name'              => User::find($order->user_id)->name ?? '',
                'phone'             => User::find($order->user_id)->phone ?? '',
                'email'             => User::find($order->user_id)->email ?? '',
                'is_feed'           => $faker->randomElement($array = array(true, false)),
                'is_travel'         => $faker->randomElement($array = array(true, false)),
                'is_member'         => $faker->randomElement($array = array(true, false)),
            ]);
        }
    }
}
