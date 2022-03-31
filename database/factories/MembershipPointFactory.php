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
            'membership_id' => 17220,
            'value' => $status ? 1000 : 100,
            'status' => $status ? 'purchase' : 'redeem'
        ];
    }
}