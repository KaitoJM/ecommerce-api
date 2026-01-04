<?php

use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

describe('Register Customer', function() {
    it ('creates a new user and customer if user is authenticated', function() {
        $response = postJson('/api/site/register', [
            'first_name' => 'John',
            'last_name' => 'doe',
            'email' => 'customer@test.com',
            'password' => '13123123',
        ]);

        $response->assertStatus(201);
        assertDatabaseHas('users', [
            'name' => 'John doe',
            'email' => 'customer@test.com',
            'role' => 'customer'
        ]);
        $response->assertJsonFragment([
            'first_name' => 'John',
            'last_name' => 'doe',
        ]);
    });

    it ('returns a 422 error if the request has no email', function() {
        $response = postJson('/api/site/register', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'email' => ['The email field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no password', function() {
        $response = postJson('/api/site/register', [
            'email' => 'test@test.com'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'password' => ['The password field is required.'],
        ]);
    });
});
