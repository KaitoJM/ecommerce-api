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
});
