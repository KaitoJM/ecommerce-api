<?php

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Order Items', function() {
    it('returns a list of order items', function() {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $orderUser = User::factory()->create(['role' => 'customer']);
        $orderCustomer = Customer::factory()->create(['user_id' => $orderUser->id]);
        $cart = Cart::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $orderCustomer->id
        ]);

        OrderItem::factory(10)->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id
        ]);

        $response = actingAs($user)->getJson('/api/order-items');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });
});

describe('Create Order Item', function() {
    it ('creates a new order item if user is authenticated', function() {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $cartUser = User::factory()->create(['role' => 'customer']);
        $orderCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $orderCustomer->id
        ]);

        $response = actingAs($user)->postJson('/api/order-items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_snapshot_name' => 'sample-product-name',
            'product_snapshot_price' => 1000,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_snapshot_name' => 'sample-product-name',
            'product_snapshot_price' => 1000,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);
    });
});

describe('Get Order Item', function() {
    it ('returns an order item if it exists', function() {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cartCustomer->id
        ]);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'product_snapshot_name' => 'sample-product-name',
            'product_snapshot_price' => 1000,
            'quantity' => 1
        ]);

        $response = actingAs($user)->getJson('/api/order-items/' . $orderItem->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'product_snapshot_name' => 'sample-product-name',
            'quantity' => 1
        ]);
    });

    it ('returns a 404 error if the order item is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/order-items/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order item not found',
        ]);
    });
});

describe('Update Order Item', function() {
    it ('updates an order item if user is authenticated', function() {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $cartUser = User::factory()->create(['role' => 'customer']);
        $orderCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $orderCustomer->id
        ]);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'product_snapshot_name' => 'sample-product-name',
            'product_snapshot_price' => 1000,
            'quantity' => 1
        ]);

        $response = actingAs($user)->putJson('/api/order-items/' . $orderItem->id, [
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'product_snapshot_name' => 'sample-product-name',
            'quantity' => 2
        ]);
    });

    it ('returns a 404 error if the user is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/order-items/999999', [
            'quantity' => 2
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order item not found',
        ]);
    });
});

describe('Delete Order Item', function() {
    it ('deletes an order item if user is authenticated', function() {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $cartUser = User::factory()->create(['role' => 'customer']);
        $orderCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $orderCustomer->id
        ]);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'product_snapshot_name' => 'sample-product-name',
            'product_snapshot_price' => 1000,
            'quantity' => 1
        ]);

        $response = actingAs($user)->deleteJson('/api/order-items/' . $orderItem->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the order item is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/order-items/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order item not found',
        ]);
    });
});
