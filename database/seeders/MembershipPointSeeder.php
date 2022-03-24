<?php

namespace Database\Seeders;

use App\Models\MembershipPoint;
use Illuminate\Database\Seeder;

class MembershipPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MembershipPoint::factory()->count(20)->create();
    }
}
