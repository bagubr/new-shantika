<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FleetClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Super Executive' => 15000,
            'Executive' => 15000,
            'Suite Class' => 15000,
        ];
        foreach ($data as $key => $value) {
            DB::table('fleet_classes')->insert([
                'name' => $key,
                'price_food' => $value
            ]);
        }
    }
}
