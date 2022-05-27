<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminShantikaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Admin::create([
            'name' => "CAN CREATIVE",
            'email' => 'cancreative@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole('superadmin');
    }
}
