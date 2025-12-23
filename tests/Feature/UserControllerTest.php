<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Get Users', function() {
    it('returns a list of users', function() {
        $user = User::factory()->create();
        User::factory(10)->create();

        $response = actingAs($user)->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of users with search', function() {
        $user = User::factory()->create();

        User::factory()->create([
            'name' => 'Test User',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Test2 User',
            'role' => 'staff'
        ]);

        $response = actingAs($user)->getJson('/api/users?search=Test2');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test2 User',
        ]);
    });
});

describe('Create User', function() {
    it ('creates a new user if user is authenticated', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '123123123',
            'role' => 'admin'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Test User',
        ]);
    });

    it ('returns a 422 error if the request has no name', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/users', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'name' => ['The name field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no email', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/users', [
            'name' => 'Test User'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'email' => ['The email field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no password', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'password' => ['The password field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no role', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '13123123'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'role' => ['The role field is required.'],
        ]);
    });

    it ('returns a 422 error if the selected role is not a valid role', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '13123123',
            'role' => 'manager'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'role' => ['The selected role is invalid.'],
        ]);
    });
});

describe('Get User', function() {
    it ('returns a user if it exists', function() {
        $user = User::factory()->create();

        $user1 = User::factory()->create([
            'name' => 'Test User',
        ]);

        $response = actingAs($user)->getJson('/api/users/' . $user1->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test User',
        ]);
    });

    it ('returns a 404 error if the user is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/users/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'User not found',
        ]);
    });
});

describe('Update User', function() {
    it ('updates a user if user is authenticated', function() {
        $user = User::factory()->create();
        $user1 = User::factory()->create([
            'name' => 'Test User',
        ]);

        $response = actingAs($user)->putJson('/api/users/' . $user1->id, [
            'name' => 'Updated User',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated User',
        ]);
    });

    it ('returns a 404 error if the user is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/users/999999', [
            'name' => 'Updated User',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'User not found',
        ]);
    });
});

describe('Delete User', function() {
    it ('deletes a user if user is authenticated', function() {
        $user = User::factory()->create();
        $user1 = User::factory()->create([
            'name' => 'Test user',
        ]);
        $response = actingAs($user)->deleteJson('/api/users/' . $user1->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the user is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/users/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'User not found',
        ]);
    });
});
