<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

describe("Create Order in Site", function() {
    it("creates an order", function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 2;

        $status = OrderStatus::factory()->create(['status' => 'Pending']);
        $discount_total = 1000;
        $tax_total = 500;

        $response = actingAs($user)->postJson('/api/site/orders', [
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total,
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_specification_id' => $productSpecification->id,
                    'quantity' => $quantity
                ]
            ]
        ]);

        $subtotal = $productSpecification->price * $quantity;
        $total = $subtotal - $discount_total + $tax_total;

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'status_id' => (string)$status->id,
            'customer_id' => (string)$customer->id,
            'is_guest' => false,
            'email' => 'sampleemail@example.com',
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        assertDatabaseHas('product_specifications', ['id' => $productSpecification->id, 'stock' => 8]);
    });

    it("throws an error if one order item does not satisfy the quantity and stock", function() {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 11;

        OrderStatus::factory()->create(['status' => 'Pending']);

        $discount_total = 1000;
        $tax_total = 500;

        $response = actingAs($user)->postJson('/api/site/orders', [
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total,
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_specification_id' => $productSpecification->id,
                    'quantity' => $quantity
                ]
            ]
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => 'Not enough stock for product specification with id #' . $productSpecification->id
        ]);
    });
});

describe("Create Order in Site as Guest", function() {
    it("creates an order as guest", function() {
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 2;

        $status = OrderStatus::factory()->create(['status' => 'Pending']);
        $discount_total = 1000;
        $tax_total = 500;

        $response = postJson('/api/site/order-guest', [
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total,
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_specification_id' => $productSpecification->id,
                    'quantity' => $quantity
                ]
            ]
        ]);

        $subtotal = $productSpecification->price * $quantity;
        $total = $subtotal - $discount_total + $tax_total;

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'status_id' => (string)$status->id,
            'is_guest' => true,
            'email' => 'sampleemail@example.com',
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        assertDatabaseHas('product_specifications', ['id' => $productSpecification->id, 'stock' => 8]);
    });

    it("throws an error if one order item does not satisfy the quantity and stock", function() {
        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'price' => 8000,
            'stock' => 10
        ]);

        $quantity = 11;

        OrderStatus::factory()->create(['status' => 'Pending']);

        $discount_total = 1000;
        $tax_total = 500;

        $response = postJson('/api/site/order-guest', [
            'email' => 'sampleemail@example.com',
            'discount_total' => $discount_total,
            'tax_total' => $tax_total,
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_specification_id' => $productSpecification->id,
                    'quantity' => $quantity
                ]
            ]
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => 'Not enough stock for product specification with id #' . $productSpecification->id
        ]);
    });
});
