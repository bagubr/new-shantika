<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Models\UserAgent;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name'  => $faker->name,
                'phone' => $faker->e164PhoneNumber,
                'email' => $faker->email,
                'avatar' => '',
                'token' => $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}'),
                'fcm_token' => $faker->sha256,
                'birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'gender' => $faker->randomElement($array = array('Male', 'Female')),
                'address' => $faker->address,
            ]);
            UserAgent::create([
                'user_id' => $user->id,
                'agency_id' => Agency::all()->random()->id
            ]);
            NotificationSetting::create([
                'user_id' => $user->id,
            ]);
        }
    }
}
