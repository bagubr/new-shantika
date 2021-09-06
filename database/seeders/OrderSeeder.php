<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\FleetRoute;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Route;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\OrderPriceDistribution;
use App\Models\TimeClassification;
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
        for ($i = 0; $i < 100; $i++) {
            $route = FleetRoute::all()->random();
            $date = date('Y-m-d H:i:s');
            $order = Order::create([
                'user_id'           => User::all()->random()->id,
                'fleet_route_id'    => $route->id,
                'code_order'        => 'STK-' . date('YmdHis'),
                'status'            => $faker->randomElement($array = array('PENDING', 'EXPIRED', 'PAID', 'CANCELED', 'EXCHANGED')),
                // 'status'            => 'PAID',
                'price'             => $route->price,
                'expired_at'        => date('Y-m-d H:i:s', strtotime($date . ' +3 day')),
                'reserve_at'        => Carbon::now()->subDays(rand(1, 200)),
                'departure_agency_id'   => Agency::all()->random()->id,
                'destination_agency_id' => Agency::all()->random()->id,
                'time_classification_id' => TimeClassification::all()->random()->id

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

            OrderPriceDistribution::create([
                'order_id'      => $order->id,
                'for_food'      => 10000,
                'for_travel'    => 10000,
                'for_member'    => 10000,
                'for_agent'     => 10000,
                'for_owner'     => $order->price - 40000,
                'ticket_only'   => $order->price,
                'deposited_at'  => null

            ]);
        }
    }
}
