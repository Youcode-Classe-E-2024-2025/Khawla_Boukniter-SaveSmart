<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

uses(TestCase::class, RefreshDatabase::class);

test('register with personnal account', function () {
    $response = $this->post('/register', [
        'name' => 'user',
        'email' => 'email@gmail.com',
        'password' => 'passwoed123',
        'account_type' => 'personal',
    ]);

    $response->assertRedirect('/profile');

    $this->assertDatabaseHas('users', [
        'email' => 'email@gmail.com',
        'account_type' => 'personal',
    ]);
});

test('login', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password')
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);

    $response->assertRedirect('/profile');

    $this->assertAuthenticatedAs($user);
});

test('logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/logout');
    Auth::logout();

    $response->assertRedirect('/login');

    $this->assertFalse(Auth::check());
});

test('register with family account', function () {
    $response = $this->post('/register', [
        'name' => 'owner',
        'email' => 'family@gmail.com',
        'password' => 'password',
        'account_type' => 'family'
    ]);

    $response->assertRedirect('/family/create');

    $this->assertDatabaseHas('users', [
        'email' => 'family@gmail.com',
        'account_type' => 'family',
    ]);
});
