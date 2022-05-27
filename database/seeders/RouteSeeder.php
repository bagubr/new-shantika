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
            $route = Route::create([
                'name'  => 'test'
            ]);
            FleetRoute::create([
                'route_id'          => $route->id,
                'fleet_detail_id'   => $value->id,
            ]);
            $checkpoints = '';
            for ($i = 1; $i <= random_int(1, 5); $i++) {
                $checkpoint = Checkpoint::create([
                    'route_id'  => $route->id,
                    'agency_id' => Agency::whereHas('city', function ($q) {
                        $q->where('area_id', 1);
                    })->inRandomOrder()->first()->id,
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
