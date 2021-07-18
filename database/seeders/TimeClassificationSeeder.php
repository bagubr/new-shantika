<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeClassification;
class TimeClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimeClassification::create([
            'name'          => 'Pagi',
            'time_start'    => '09:00',
            'time_end'      => '19:00',
        ]);

        TimeClassification::create([
            'name' => 'Malam',
            'time_start' => '21:00',
            'time_end' => '07:00',
        ]);
    }
}
