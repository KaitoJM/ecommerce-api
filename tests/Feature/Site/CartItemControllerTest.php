<?php

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Add To Cart', function() {
    it ('creates a new cart item for customer', function() {
        $user = User::factory()->create(['role' => 'customer']);

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);

        $cartCustomer = Customer::factory()->create(['user_id' => $user->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active'
        ]);

        $response = actingAs($user)->postJson('/api/site/cart-items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'qty' => 1
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'cart_id' => (string) $cart->id,
            'product_id' => (string) $product->id,
            'product_specification_id' => (string) $productSpecification->id,
            'quantity' => 1
        ]);
    });

    it ('throws an error if the provided product specification is not related to the provided product', function() {
        $user = User::factory()->create(['role' => 'customer']);

        $product = Product::factory()->create();
        $product2 = Product::factory()->create(); // wrong product owner
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product2->id
        ]);

        $cartCustomer = Customer::factory()->create(['user_id' => $user->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active'
        ]);

        $response = actingAs($user)->postJson('/api/site/cart-items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'qty' => 1
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => 'The provided product does not own this specification.'
        ]);
    });
});
