<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Faker\Factory as Faker;
class ArticleSeeder extends Seeder
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
            Article::create([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'image' => '',
                'description' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
            ]);
        }
    }
}
