<?php

namespace Database\Seeders;

use App\Models\MembershipHistory;
use Illuminate\Database\Seeder;

class MembershipHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MembershipHistory::factory()->count(30)->create();
    }
}
