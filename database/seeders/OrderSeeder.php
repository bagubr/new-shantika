<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
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
        for ($i=0; $i < 20; $i++) { 
            $route = Route::all()->random()->first();
            $date = date('Y-m-d H:i:s');
            $order = Order::create([
                'user_id'    => User::all()->random()->id,
                'route_id'   => $route->id,
                'code_order' => '',
                'status'     => $faker->randomElement($array = array ('PENDING', 'EXPIRED', 'PAID', 'CANCELED', 'EXCHANGED')),
                'price'      => $route->price,
                'expired_at' => date('Y-m-d H:i:s', strtotime($date . ' +3 day')),
                'reserve_at' => $date,
            ]);

            OrderDetail::create([
                'order_id'          => $order->id,
                'layout_chair_id'   => LayoutChair::random()->all()->id,
                'code_ticket'       => '',
                'name'              => User::find($order->user_id)->name??'',
                'phone'             => User::find($order->user_id)->phone??'',
                'email'             => User::find($order->user_id)->email??'',
                'is_feed'           => $faker->randomElement($array = array (true, false)),
                'is_travel'         => $faker->randomElement($array = array (true, false)),
                'is_member'         => $faker->randomElement($array = array (true, false)),
            ]);
        }
    }
}
