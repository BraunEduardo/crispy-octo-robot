<?php

use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use Database\Factories\CardFactory;
use Database\Factories\TransactionProductFactory;
use Database\Seeders\GatewaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $user = User::factory()->create();
    $this->actingAs($user);
});

test('lista compras', function () {
    Transaction::factory()->count(3)->create();

    $response = $this->get(route('transactions.index'));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            '*' => [
                'id',
                'client',
                'gateway',
                'external_id',
                'status',
                'amount',
                'card_last_numbers',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
});

test('cria um compra', function () {
    $this->seed(GatewaySeeder::class);
    $card = CardFactory::new();
    $client = Client::factory()->make();
    $transaction = Transaction::factory()->make();
    $transaction_products = [
        'products' => TransactionProductFactory::new()->count(5)->raw(),
    ];

    $dados = $client->getAttributes() +
        $card->raw() +
        $transaction->getAttributes() +
        $transaction_products;
dd(json_encode($dados));
    $response = $this->post(route('transactions.store'), $dados);

    $response->assertStatus(201)
        ->assertExactJsonStructure([
            'id',
            'client',
            'gateway',
            'external_id',
            'status',
            'amount',
            'card_last_numbers',
            'created_at',
            'updated_at',
        ]);

    $this->assertDatabaseHas('transactions', [
        'id' => $response->json('id'),
    ]);
});

test('mostra um compra', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->get(route('transactions.show', $transaction->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'client',
            'gateway',
            'external_id',
            'status',
            'amount',
            'card_last_numbers',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('exclui um compra', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->delete(route('transactions.destroy', $transaction->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'client',
            'gateway',
            'external_id',
            'status',
            'amount',
            'card_last_numbers',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

    $this->assertSoftDeleted($transaction);
});

test('compra não encontrado', function() {
    $response = $this->get(route('transactions.show', 999));
    $response->assertStatus(404);

    $response = $this->put(route('transactions.update', 999), ['name' => 'test']);
    $response->assertStatus(404);

    $response = $this->delete(route('transactions.destroy', 999));
    $response->assertStatus(404);
});