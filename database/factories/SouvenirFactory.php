<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SouvenirFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(5),
            'image_name' => Str::random(10) . '.jpg',
            'description' => $this->faker->text(10),
            'point' => rand(50,100),
            'quantity' => rand(10,25)
        ];
    }
}