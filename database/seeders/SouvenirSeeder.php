<?php

namespace Database\Seeders;

use App\Models\Souvenir;
use Illuminate\Database\Seeder;

class SouvenirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Souvenir::factory()->count(10)->create();
    }
}