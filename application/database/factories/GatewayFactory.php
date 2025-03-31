<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gateway>
 */
class GatewayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [];

        for ($i=0; $i < fake()->numberBetween(2, 5); $i++) { 
            $data[fake()->word()] = fake()->words();
        }

        return [
            'name' => fake()->company(),
            'is_active' => fake()->boolean(),
            'priority' => fake()->randomDigit(),
            'url' => fake()->url(),
            'data' => $data,
        ];
    }
}
