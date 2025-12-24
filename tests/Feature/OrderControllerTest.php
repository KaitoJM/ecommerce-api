<?php

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Orders', function() {
    it('returns a list of orders', function() {
        $user = User::factory()->create();

        $orderUser = User::factory()->create(['role' => 'customer']);
        $orderCustomer = Customer::factory()->create(['user_id' => $orderUser->id]);
        $cart = Cart::factory()->create();
        Order::factory(10)->create([
            'cart_id' => $cart->id,
            'customer_id' => $orderCustomer->id
        ]);

        $response = actingAs($user)->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });
});

describe('Create Order', function() {
    it ('creates a new order if user is authenticated', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create(['customer_id' => $cartCustomer->id]);
        $status = OrderStatus::factory()->create();

        $response = actingAs($user)->postJson('/api/orders', [
            'cart_id' => $cart->id,
            'status_id' => $status->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'status_id' => $status->id
        ]);
    });
});

describe('Get Order', function() {
    it ('returns an order if it exists', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active',
        ]);
        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'status_id' => $status->id,
        ]);

        $response = actingAs($user)->getJson('/api/orders/' . $order->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'status_id' => $status->id,
        ]);
    });

    it ('returns a 404 error if the order is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/orders/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order not found',
        ]);
    });
});

describe('Update Order', function() {
    it ('updates an order if user is authenticated', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active',
        ]);
        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'status_id' => $status->id,
        ]);

        $status2 = OrderStatus::factory()->create();

        $response = actingAs($user)->putJson('/api/orders/' . $order->id, [
            'status_id' => $status2->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'status_id' => $status2->id,
        ]);
    });

    it ('returns a 404 error if the order is not found', function() {
        $user = User::factory()->create();
        $status2 = OrderStatus::factory()->create();
        $response = actingAs($user)->putJson('/api/orders/999999', [
            'status_id' => $status2->id,
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order not found',
        ]);
    });
});

describe('Delete Order', function() {
    it ('deletes an order if user is authenticated', function() {
        $user = User::factory()->create();

        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active',
        ]);
        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'status_id' => $status->id,
        ]);

        $response = actingAs($user)->deleteJson('/api/orders/' . $order->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the order is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/orders/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order not found',
        ]);
    });
});
