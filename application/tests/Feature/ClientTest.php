<?php

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $user = User::factory()->create();
    $this->actingAs($user);
});

test('lista clientes', function () {
    Client::factory()->count(3)->create();

    $response = $this->get(route('clients.index'));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
});

test('cria um cliente', function () {
    $client = Client::factory()->make();

    $dados = $client->getAttributes();

    $response = $this->post(route('clients.store'), $dados);

    $response->assertStatus(201)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ]);

    $this->assertDatabaseHas('clients', [
        'id' => $response->json('id'),
    ]);
});

test('mostra um cliente', function () {
    $client = Client::factory()->create();

    $response = $this->get(route('clients.show', $client->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'transactions',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('atualiza um cliente', function () {
    $client = Client::factory()->create();

    $update = [
        'name' => fake()->company(),
    ];

    $response = $this->put(route('clients.update', $client->id), $update);

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('name', $update['name']);

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'name' => $update['name'],
    ]);
});

test('exclui um cliente', function () {
    $client = Client::factory()->create();

    $response = $this->delete(route('clients.destroy', $client->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

    $this->assertSoftDeleted($client);
});

test('cliente não encontrado', function() {
    $response = $this->get(route('clients.show', 999));
    $response->assertStatus(404);

    $response = $this->put(route('clients.update', 999), ['name' => 'test']);
    $response->assertStatus(404);

    $response = $this->delete(route('clients.destroy', 999));
    $response->assertStatus(404);
});