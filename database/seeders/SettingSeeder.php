<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'member' => 10000,
            'travel' => 20000,
            'booking_expired_duration' => 15,
            'commision' => 0.08
        ]);
    }
}
