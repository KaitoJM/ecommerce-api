<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Cart Items', function() {
    it('returns items for the provided cart id', function() {
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

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response = actingAs($user)->getJson('/api/site/cart-items?cart_id=' . $cart->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    });
});

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

describe('Update Cart Item', function() {
    it ('updates a cart item if user is authenticated', function() {
        $user = User::factory()->create(['role' => 'customer']);

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active'
        ]);

        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response = actingAs($user)->putJson('/api/site/cart-items/' . $cartItem->id, [
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 2
        ]);
    });

    it ('returns a 404 error if the user is not found', function() {
        $user = User::factory()->create(['role' => 'customer']);
        $response = actingAs($user)->putJson('/api/site/cart-items/999999', [
            'quantity' => 2
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart item not found',
        ]);
    });
});

describe('Delete Cart Item', function() {
    it ('deletes a cart item if user is authenticated', function() {
        $user = User::factory()->create(['role' => 'customer']);

        $product = Product::factory()->create();
        $productSpecification = ProductSpecification::factory()->create([
            'product_id' => $product->id
        ]);
        $cartUser = User::factory()->create(['role' => 'customer']);
        $cartCustomer = Customer::factory()->create(['user_id' => $cartUser->id]);
        $cart = Cart::factory()->create([
            'customer_id' => $cartCustomer->id,
            'status' => 'active'
        ]);

        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response = actingAs($user)->deleteJson('/api/site/cart-items/' . $cartItem->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the cart item is not found', function() {
        $user = User::factory()->create(['role' => 'customer']);
        $response = actingAs($user)->deleteJson('/api/site/cart-items/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart item not found',
        ]);
    });
});
