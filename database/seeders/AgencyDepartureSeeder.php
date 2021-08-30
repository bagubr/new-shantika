<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgencyDepartureTime;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AgencyDepartureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = Agency::all();
        $faker = Faker::create('id_ID');
        foreach ($data as $key => $value) {
            AgencyDepartureTime::create([
                'agency_id'     => $data->random()->id,
                'departure_at'  => $faker->time(),
                'time_classification_id' => 1
            ]);
        }
    }
}
