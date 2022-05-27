<?php

namespace Database\Seeders;

use App\Models\Membership;
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
        MembershipPoint::factory()->count(1)->create();
        $count = MembershipPoint::where('membership_id', 17220)->sum('value');
        $update = Membership::where('id', 17220)->update(['sum_point' => $count]);
    }
}