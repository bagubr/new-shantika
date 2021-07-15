<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FleetClass;
use App\Models\Layout;
use Faker\Factory as Faker;
class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $data = [
            'A1',
            'A2',
            'A3',
            'B1',
            'B2',
            'B3',
            'C1',
            'C2',
            'C3',
            'D1',
            'D2',
            'D3',
        ];

        foreach ($data as $key => $value) {
            DB::table('fleets')->insert([
                'name' => $value,
                'fleet_class_id' => FleetClass::all()->random()->id,
                'layout_id' => Layout::all()->random()->id,
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'image' => '',
            ]);
        }
    }
}
