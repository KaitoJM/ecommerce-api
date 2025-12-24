<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Cart Items', function() {
    it('returns a list of cart items', function() {
        $user = User::factory()->create();

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

        CartItem::factory(10)->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response = actingAs($user)->getJson('/api/cart-items');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });
});

describe('Create Cart Item', function() {
    it ('creates a new cart item if user is authenticated', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->postJson('/api/cart-items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);
    });

    it ('returns a 422 error if the request has no cart_id', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->postJson('/api/cart-items', [
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'cart_id' => ['The cart id field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no product id', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->postJson('/api/cart-items', [
            'cart_id' => $cart->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'product_id' => ['The product id field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no password', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->postJson('/api/cart-items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'product_specification_id' => ['The product specification id field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no quantity', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->postJson('/api/cart-items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'quantity' => ['The quantity field is required.'],
        ]);
    });
});

describe('Get Cart Item', function() {
    it ('returns a cart item if it exists', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->getJson('/api/cart-items/' . $cartItem->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_specification_id' => $productSpecification->id,
            'quantity' => 1
        ]);
    });

    it ('returns a 404 error if the cart item is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/cart-items/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart item not found',
        ]);
    });
});

describe('Update Cart Item', function() {
    it ('updates a cart item if user is authenticated', function() {
        $user = User::factory()->create();

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

        $response = actingAs($user)->putJson('/api/cart-items/' . $cartItem->id, [
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
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/cart-items/999999', [
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
        $user = User::factory()->create();

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

        $response = actingAs($user)->deleteJson('/api/cart-items/' . $cartItem->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the cart item is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/cart-items/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Cart item not found',
        ]);
    });
});
