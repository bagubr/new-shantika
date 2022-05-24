<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => User::whereHas('membership')->whereDoesntHave('agencies')->inRandomOrder()->first()->id,
            'agency_id' => User::whereHas('agencies')->inRandomOrder()->first()->id
        ];
    }
}
