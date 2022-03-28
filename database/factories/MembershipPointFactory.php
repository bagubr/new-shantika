<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = rand(0,1);
        return [
            'membership_id' => 17219,
            'value' => $status ? 10000 : 1000,
            'status' => $status ? 'purchase' : 'redeem'
        ];
    }
}