<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([AdminSeeder::class]);
        $this->call([AgenciesSeeder::class]);
        $this->call([UsersSeeder::class]);
        $this->call([FleetClassSeeder::class]);
        $this->call([LayoutSeeder::class]);
        $this->call([FleetSeeder::class]);
        $this->call([RouteSeeder::class]);
        $this->call([CustomerMenuSeeder::class]);
        $this->call([SliderSeeder::class]);
        $this->call([ArticleSeeder::class]);
        $this->call([TestimonialSeeder::class]);
        $this->call([ChatSeeder::class]);
    }
}
