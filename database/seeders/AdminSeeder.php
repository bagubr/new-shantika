<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['superadmin', 'admin_satrio', 'admin_indra', 'admin_bagu'];
        foreach ($data as $key => $value) {
            $admin = Admin::create([
                'name' => $value,
                'email' => $value . '@gmail.com',
                'password' => Hash::make('12345678')
            ]);
            $admin->assignRole('superadmin');
        }
    }
}
