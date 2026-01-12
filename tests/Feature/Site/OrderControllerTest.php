<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

describe("Create Order From Cart", function() {
    it("creates an order from cart", function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'active'
        ]);
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 2;

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => $quantity
        ]);

        $status = OrderStatus::factory()->create(['status' => 'Pending']);

        $discount_total = 1000;
        $tax_total = 500;

        $response = postJson('/api/site/orders', [
            'cart_id' => $cart->id,
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total
        ]);

        $subtotal = $productSpecification->price * $quantity;
        $total = $subtotal - $discount_total + $tax_total;

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'status_id' => $status->id,
            'customer_id' => $customer->id,
            'email' => 'sampleemail@example.com',
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        assertDatabaseHas('product_specifications', ['id' => $productSpecification->id, 'stock' => 8]);
    });

    it("creates an order from cart for guest user", function() {
        $cart = Cart::factory()->create([
            'session_id' => 'some-session-token',
            'status' => 'active'
        ]);
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 2;

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => $quantity
        ]);

        $status = OrderStatus::factory()->create(['status' => 'Pending']);

        $discount_total = 1000;
        $tax_total = 500;

        $response = postJson('/api/site/orders', [
            'cart_id' => $cart->id,
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total
        ]);

        $subtotal = $productSpecification->price * $quantity;
        $total = $subtotal - $discount_total + $tax_total;

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'status_id' => $status->id,
            'session_id' => 'some-session-token',
            'email' => 'sampleemail@example.com',
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        assertDatabaseHas('product_specifications', ['id' => $productSpecification->id, 'stock' => 8]);
    });

    it("throws an error if cartId already registered to other orders", function() {
        $cart = Cart::factory()->create([
            'session_id' => 'some-session-token',
            'status' => 'active'
        ]);
        Order::factory()->create(['cart_id' => $cart->id]);
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 2;

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => $quantity
        ]);

        $status = OrderStatus::factory()->create(['status' => 'Pending']);

        $discount_total = 1000;
        $tax_total = 500;

        $response = postJson('/api/site/orders', [
            'cart_id' => $cart->id,
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => 'Cart ID already exist in order records.',
        ]);
    });

    it("throws an error if one order item does not satisfy the quantity and stock", function() {
        $cart = Cart::factory()->create([
            'session_id' => 'some-session-token',
            'status' => 'active'
        ]);
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 11;

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => $quantity
        ]);

        OrderStatus::factory()->create(['status' => 'Pending']);

        $discount_total = 1000;
        $tax_total = 500;

        $response = postJson('/api/site/orders', [
            'cart_id' => $cart->id,
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => 'Not enough stock for product specification with id #' . $productSpecification->id
        ]);
    });
});
