<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\User;
use Faker\Factory as Faker;
class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=0; $i < 5; $i++) { 
            Testimonial::create([
                'user_id'   => User::all()->random()->id,
                'review'    => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
                'rating'    => $faker->randomElement($array = array (1,2,3,4,5)),
                'image'     => '',
            ]);
        }
    }
}
