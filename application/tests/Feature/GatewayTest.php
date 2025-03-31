<?php

use App\Models\Gateway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $user = User::factory()->create();
    $this->actingAs($user);
});

test('lista gateways', function () {
    Gateway::factory()->count(3)->create();

    $response = $this->get(route('gateways.index'));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            '*' => [
                'id',
                'name',
                'is_active',
                'priority',
                'url',
                'data',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
});

test('mostra um gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->get(route('gateways.show', $gateway->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'is_active',
            'priority',
            'url',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('atualiza um gateway', function () {
    $gateway = Gateway::factory()->create();

    $update = [
        'name' => fake()->company(),
    ];

    $response = $this->put(route('gateways.update', $gateway->id), $update);

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'is_active',
            'priority',
            'url',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('name', $update['name']);

    $this->assertDatabaseHas('gateways', [
        'id' => $gateway->id,
        'name' => $update['name'],
    ]);
});

test('exclui um gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->delete(route('gateways.destroy', $gateway->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'is_active',
            'priority',
            'url',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

    $this->assertSoftDeleted($gateway);
});

test('gateway não encontrado', function() {
    $response = $this->get(route('gateways.show', 999));
    $response->assertStatus(404);

    $response = $this->put(route('gateways.update', 999), ['name' => 'test']);
    $response->assertStatus(404);

    $response = $this->delete(route('gateways.destroy', 999));
    $response->assertStatus(404);
});

test('ativa/inativa um gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->put(route('gateways.toggle', $gateway->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'is_active',
            'priority',
            'url',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('is_active', !$gateway->is_active);

    $this->assertDatabaseHas('gateways', [
        'id' => $gateway->id,
        'is_active' => !$gateway->is_active,
    ]);

    $gateway->refresh();

    $response = $this->put(route('gateways.toggle', $gateway->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'is_active',
            'priority',
            'url',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('is_active', !$gateway->is_active);

    $this->assertDatabaseHas('gateways', [
        'id' => $gateway->id,
        'is_active' => !$gateway->is_active,
    ]);
});

test('altera prioridade de um gateway', function () {
    $gateway = Gateway::factory()->create();

    $response = $this->put(route('gateways.changePriority', $gateway->id), [
        'priority' => $gateway->priority + 1,
    ]);

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'is_active',
            'priority',
            'url',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('priority', $gateway->priority + 1);

    $this->assertDatabaseHas('gateways', [
        'id' => $gateway->id,
        'priority' => $gateway->priority + 1,
    ]);
});