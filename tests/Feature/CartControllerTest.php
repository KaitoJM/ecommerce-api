<?php

use App\Models\Cart;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Carts', function() {
    it('returns a list of carts', function() {
        $user = User::factory()->create();
        Cart::factory(10)->create();

        $response = actingAs($user)->getJson('/api/carts');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });
});

describe('Create Cart', function() {
    it ('creates a new cart if user is authenticated', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $response = actingAs($user)->postJson('/api/carts', [
            'user_id' => $cartUser->id,
            'status' => 'active',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'user_id' => $cartUser->id,
        ]);
    });

    it ('returns a 422 error if the selected status is not a valid status', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $response = actingAs($user)->postJson('/api/carts', [
            'user_id' => $cartUser->id,
            'status' => 'invalid-status',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => ['The selected status is invalid.'],
        ]);
    });
});

describe('Get Cart', function() {
    it ('returns a cart if it exists', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cart = Cart::factory()->create([
            'user_id' => $cartUser->id,
            'status' => 'active',
        ]);

        $response = actingAs($user)->getJson('/api/carts/' . $cart->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $cartUser->id,
        ]);
    });

    it ('returns a 404 error if the cart is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/carts/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart not found',
        ]);
    });
});

describe('Update Cart', function() {
    it ('updates a cart if cart is authenticated', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cart = Cart::factory()->create([
            'user_id' => $cartUser->id,
            'status' => 'active',
        ]);

        $response = actingAs($user)->putJson('/api/carts/' . $cart->id, [
            'status' => 'converted',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $cartUser->id,
            'status' => 'converted',
        ]);
    });

    it ('returns a 404 error if the cart is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/carts/999999', [
            'status' => 'converted',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart not found',
        ]);
    });
});

describe('Delete Cart', function() {
    it ('deletes a cart if user is authenticated', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cart = Cart::factory()->create([
            'user_id' => $cartUser->id,
            'status' => 'active',
        ]);

        $response = actingAs($user)->deleteJson('/api/carts/' . $cart->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the cart is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/carts/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart not found',
        ]);
    });
});
