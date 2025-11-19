<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $images = [
        'banana.png',
        'grapes.png',
        'kiwi.png',
        'melon.png',
        'muscat.png',
        'orange.png',
        'peach.png',
        'pineapple.png',
        'strawberry.png',
        'watermelon.png',
        ];

    return [
        'name' => $this->faker->word(),
        'price' => $this->faker->numberBetween(600,10000),
        'description' => $this->faker->sentence(),
        'image' => $this->faker->randomElement($images),
        ];
    }
}

