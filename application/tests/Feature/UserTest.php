<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $user = User::factory()->create();
    $this->actingAs($user);
});

test('lista usuários', function () {
    User::factory()->count(3)->create();

    $response = $this->get(route('users.index'));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
});

test('cria um usuário', function () {
    $user = User::factory()->make();

    $dados = $user->getAttributes();

    $response = $this->post(route('users.store'), $dados);

    $response->assertStatus(201)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $response->json('id'),
    ]);
});

test('mostra um usuário', function () {
    $user = User::factory()->create();

    $response = $this->get(route('users.show', $user->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('atualiza um usuário', function () {
    $user = User::factory()->create();

    $update = [
        'name' => fake()->company(),
    ];

    $response = $this->put(route('users.update', $user->id), $update);

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('name', $update['name']);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => $update['name'],
    ]);
});

test('exclui um usuário', function () {
    $user = User::factory()->create();

    $response = $this->delete(route('users.destroy', $user->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

    $this->assertSoftDeleted($user);
});

test('usuário não encontrado', function() {
    $response = $this->get(route('users.show', 999));
    $response->assertStatus(404);

    $response = $this->put(route('users.update', 999), ['name' => 'test']);
    $response->assertStatus(404);

    $response = $this->delete(route('users.destroy', 999));
    $response->assertStatus(404);
});