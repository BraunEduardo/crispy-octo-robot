<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $user = User::factory()->create();
    $this->actingAs($user);
});

test('lista produtos', function () {
    Product::factory()->count(3)->create();

    $response = $this->get(route('products.index'));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            '*' => [
                'id',
                'name',
                'amount',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
});

test('cria um produto', function () {
    $product = Product::factory()->make();

    $dados = $product->getAttributes();

    $response = $this->post(route('products.store'), $dados);

    $response->assertStatus(201)
        ->assertExactJsonStructure([
            'id',
            'name',
            'amount',
            'created_at',
            'updated_at',
        ]);

    $this->assertDatabaseHas('products', [
        'id' => $response->json('id'),
    ]);
});

test('mostra um produto', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('products.show', $product->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'amount',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('atualiza um produto', function () {
    $product = Product::factory()->create();

    $update = [
        'name' => fake()->company(),
    ];

    $response = $this->put(route('products.update', $product->id), $update);

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'amount',
            'created_at',
            'updated_at',
            'deleted_at',
        ])
        ->assertJsonPath('name', $update['name']);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => $update['name'],
    ]);
});

test('exclui um produto', function () {
    $product = Product::factory()->create();

    $response = $this->delete(route('products.destroy', $product->id));

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'id',
            'name',
            'amount',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

    $this->assertSoftDeleted($product);
});

test('produto não encontrado', function() {
    $response = $this->get(route('products.show', 999));
    $response->assertStatus(404);

    $response = $this->put(route('products.update', 999), ['name' => 'test']);
    $response->assertStatus(404);

    $response = $this->delete(route('products.destroy', 999));
    $response->assertStatus(404);
});