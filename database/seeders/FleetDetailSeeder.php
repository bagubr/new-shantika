<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\FleetDetail;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FleetDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = Fleet::all();
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 20; $i++) {
            FleetDetail::create([
                'fleet_id' => Fleet::all()->random()->id,
                'nickname' => $faker->word,
                'plate_number' => 'H' . rand(1000, 9999) . 'AB',
            ]);
        }
    }
}
