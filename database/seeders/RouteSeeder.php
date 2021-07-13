<?php

namespace Database\Seeders;

use App\Models\Fleet;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Route;
use App\Models\Agency;
use App\Models\Checkpoint;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $departure_at = $faker->time();
        $route = Route::create([
            'name'=>date('mYhS'),
            'fleet_id'=>Fleet::inRandomOrder()->first()->id,
            'departure_at'=>$departure_at,
            'arrived_at'=>date('H:i:s', strtotime($departure_at) + 60 * (60 * $faker->randomDigit())),
            'price'=>$faker->numberBetween(50000, 500000)
        ]);
        for($i = 0; $i <= $faker->randomDigit(); $i++) {
            Checkpoint::create([
                'route_id'=>$route->id,
                'arrived_at'=>$faker->time(),
                'agency_id'=>Agency::inRandomOrder()->first()->id,
                'order'=>$i
            ]);
        }
    }
}
