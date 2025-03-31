<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Gateway;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::factory()->create();
        $gateway = Gateway::factory()->create();

        return [
            'client' => $client->id,
            'gateway' => $gateway->id,
            'status' => fake()->randomElement(Transaction::STATUSES),
            'amount' => fake()->randomFloat(),
            'card_last_numbers' => (string) fake()->randomNumber(4),
        ];
    }
}
