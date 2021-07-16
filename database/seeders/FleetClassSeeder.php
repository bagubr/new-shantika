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
            'Super Executive',
            'Executive',
            'Suite Class',
        ];
        foreach ($data as $key => $value) {
            DB::table('fleet_classes')->insert([
                'name' => $value
            ]);
        }
    }
}
