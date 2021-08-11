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
        $fleet = Fleet::get();
        foreach ($fleet as $key => $value) {
            $departure_at = $faker->time();
            $route = Route::create([
                'fleet_id'      => $value->id,
                'departure_at'  => $departure_at,
                'arrived_at'    => date('H:i:s', strtotime($departure_at) + 60 * (60 * $faker->randomDigit())),
                'price'         => $faker->numberBetween(50000, 500000),
                'area_id'       => 1
            ]);
            $checkpoints = '';
            for ($i = 0; $i <= $faker->randomDigit(); $i++) {
                $checkpoint = Checkpoint::create([
                    'route_id'  => $route->id,
                    'arrived_at' => $faker->time(),
                    'agency_id' => Agency::inRandomOrder()->first()->id,
                    'order'     => $i
                ]);
                $checkpoints .= '~' . $checkpoint->agency()->first()->name . '~';
            }
            $route->update([
                'name' => $checkpoints,
            ]);
        }
    }
}
