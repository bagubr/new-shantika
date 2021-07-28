<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Review;
use App\Models\Order;
class ReviewSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=0; $i < 10 ; $i++) { 
            Review::create([
                'order_id' => Order::all()->random()->id,
                'review'    => $faker->paragraph($nbSentences = 1, $variableNbSentences = true),
                'rating'    => $faker->randomElement($array = array (1,2,3,4,5)),
            ]);
        }
    }
}
