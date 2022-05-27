<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AreaSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Area::create([
            'name' => 'JAWA',
        ]);

        \App\Models\Area::create([
            'name' => 'JABODETABEK',
        ]);


        $route = \App\Models\Route::get();
        foreach ($route as $key => $value) {
            $value->update([
                'area_id' => \App\Models\Area::all()->random()->id,
            ]);
        }
        $faker = Faker::create('id_ID');
        $payment_type = \App\Models\PaymentType::get();
        foreach ($payment_type as $key => $value) {
            $value->update([
                'description' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
            ]);
        }
    }
}
