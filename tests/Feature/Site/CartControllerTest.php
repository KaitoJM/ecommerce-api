<?php

use App\Models\Cart;
use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Active Cart', function() {
    it('returns the active cart of the customer', function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        Cart::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'active'
        ]);

        $response = actingAs($user)->getJson('/api/site/carts-active/');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'customer_id' => $customer->id,
            'status' => 'active',
        ]);
    });

    it('created an active cart if no active cart yet and returns the active cart of the customer', function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $response = actingAs($user)->getJson('/api/site/carts-active/');

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'customer_id' => (string) $customer->id,
            'status' => 'active',
        ]);
    });
});


describe('Add Cart', function() {
    it('creates new cart', function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $response = actingAs($user)->postJson('/api/site/carts/', [
            'status' => 'active'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'customer_id' => (string) $customer->id,
            'status' => 'active',
        ]);
    });

    it('returns an error if status is active and there is already an active cart', function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        Cart::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'active'
        ]);

        $response = actingAs($user)->postJson('/api/site/carts/', [
            'status' => 'active'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Active cart already exists.'
        ]);
    });
});
