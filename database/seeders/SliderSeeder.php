<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Slider;
class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=0; $i < 10; $i++) { 
            Slider::create([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'image' => '',
                'description' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
                'type' => $faker->randomElement($array = array ('AGENT','CUST')),   
            ]);
        }
    }
}
