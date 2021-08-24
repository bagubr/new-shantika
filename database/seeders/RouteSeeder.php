<?php

namespace Database\Seeders;

use App\Models\Fleet;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Route;
use App\Models\Agency;
use App\Models\Checkpoint;
use App\Models\City;
use App\Models\FleetRoute;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $faker = Faker::create('id_ID');
        $fleet = Fleet::get();
        foreach ($fleet as $key => $value) {
            $departure_at = $faker->time();
            $departure_city_id = City::inRandomOrder()->first()->id;
            $destination_city_id = City::where('id', '!=', $departure_city_id)->inRandomOrder()->first()->id;
            $route = Route::create([
                'departure_city_id'    => $departure_city_id,
                'destination_city_id'  => $destination_city_id,
                'departure_at'  => $departure_at,
                'arrived_at'    => date('H:i:s', strtotime($departure_at) + 60 * (60 * $faker->randomDigit())),
            ]);
            FleetRoute::create([
                'route_id' => $route->id,
                'fleet_id' => $value->id,
                'price'         => $faker->numberBetween(50000, 500000),
            ]);
            for ($i = 1; $i <= $faker->randomDigit(); $i++) {
                Checkpoint::create([
                    'route_id'  => $route->id,
                    // 'arrived_at' => $faker->time(),
                    'agency_id' => Agency::inRandomOrder()->first()->id,
                    'order'     => $i
                ]);
                // $checkpoints .= '~' . $checkpoint->agency()->first()->name . '~';
            }
            $route->update([
                'name' => '~' . $route->departure_city->name . '~' . $route->destination_city->name . '~',
            ]);
        }
        DB::commit();
    }
}
