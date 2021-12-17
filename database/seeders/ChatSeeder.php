<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;
use Faker\Factory as Faker;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $data = [
            'Chats Group Pembatalan',
            'Chats Group Booking Tiket',
        ];
        foreach ($data as $key => $value) {
            Chat::create([
                'name'  => $value,
                'link'  => $faker->url,
                'type'  => 'AGENT',
                'icon'  => '',
            ]);
        }
        $data = [
            'Chats Operator',
            'Chats Customer Service',
        ];
        foreach ($data as $key => $value) {
            Chat::create([
                'name'  => $value,
                'link'  => $faker->url,
                'type'  => 'CUST',
                'icon'  => '',
            ]);
        }
    }
}
