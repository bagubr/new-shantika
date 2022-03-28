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
        MembershipPoint::factory()->count(20)->create();
        $count = MembershipPoint::where('membership_id', 17219)->sum('value');
        $update = Membership::where('id', 17219)->update(['sum_point' => $count]);
    }
}
