<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('validação', function () {
    $response = $this->post(route('login'));

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
            'password',
        ]);
});


test('sucesso', function () {
    $password = 'P@ssw0rd';
    $user = User::factory()->create(['password' =>  Hash::make($password)]);
    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'token',
        ]);
});

test('erro', function () {
    $password = 'P@ssw0rd';
    $user = User::factory()->create(['password' =>  Hash::make($password)]);
    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => $password . '2',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
        ]);
});
