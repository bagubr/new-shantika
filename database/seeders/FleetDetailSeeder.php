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
        foreach ($data as $key => $value) {
            FleetDetail::firstOrCreate(
                [
                    'fleet_id' => $data->random()->id
                ],
                [
                    'nickname' => $faker->word,
                    'plate_number' => 'H' . rand(1000, 9999) . 'AB',
                ]
            );
        }
    }
}
