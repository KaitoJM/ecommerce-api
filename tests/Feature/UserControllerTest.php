<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

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

        User::create([
            'name' => 'Test User',
            'role' => 'admin'
        ]);

        User::create([
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
