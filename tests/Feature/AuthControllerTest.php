<?php
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

describe('Login', function() {
    it('returns the user data and token when successfully logged in', function() {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $payload = [
            'email' => 'test@example.com',
            'password' => 'password'
        ];

        $response = postJson('/api/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
            'token'
        ]);

        $response->assertJsonFragment([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    });

    it('returns a 401 error if credential are incorrect.', function() {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $payload = [
            'email' => 'bademail@example.com',
            'password' => 'badpassword'
        ];

        $response = postJson('/api/login', $payload);

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'error' => 'Invalid credentials'
        ]);
    });
});

describe('Logout', function() {
    it('Removes the token of the user upon successful logout.', function () {
        // Create user
        $user = User::factory()->create();

        // Create a real Sanctum token
        $token = $user->createToken('api-token');

        // Send logout request with Bearer token
        $response = withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken,
        ])->postJson('/api/logout');

        // Assert response
        $response->assertStatus(200);

        // Assert the token was deleted
        assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    });
});
