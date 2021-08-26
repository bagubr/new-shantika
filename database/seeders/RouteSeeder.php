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
use App\Models\FleetDetail;
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
        $fleet = FleetDetail::get();
        foreach ($fleet as $key => $value) {
            $departure_at = $faker->time();
            $departure_city_id = City::inRandomOrder()->first()->id;
            $destination_city_id = City::where('id', '!=', $departure_city_id)->inRandomOrder()->first()->id;
            $route = Route::create([
                'name'  => 'test'
            ]);
            FleetRoute::create([
                'route_id' => $route->id,
                'fleet_detail_id' => $value->id,
                'price'         => $faker->numberBetween(50000, 500000),
            ]);
            $checkpoints = '';
            for ($i = 1; $i <= $faker->randomDigit(); $i++) {
                $checkpoint = Checkpoint::create([
                    'route_id'  => $route->id,
                    'agency_id' => Agency::inRandomOrder()->first()->id,
                    'order'     => $i
                ]);
                $checkpoints .= '~' . $checkpoint->agency()->first()->name . '~';
            }
            $route->update([
                'name' => $checkpoints,
            ]);
        }
        DB::commit();
    }
}
