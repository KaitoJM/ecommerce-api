<?php

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Get Product Attributes', function() {
    it('is accessible if authenticated', function() {
        $response = getJson('/api/product-attributes');

        $response->assertStatus(401);
    });

    it('returns a list of product attributes', function() {
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create();

        ProductAttribute::factory(10)->create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
        ]);

        $user = User::factory()->create();

        $response = actingAs($user)->getJson('/api/product-attributes');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });
});

describe('Create Product Attribute', function() {
    it('accessible only if the user is authenticated', function() {
        $response = postJson('/api/product-attributes', []);

        $response->assertStatus(401);
    });

    it ('creates a new product attribute if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create();

        $response = actingAs($user)->postJson('/api/product-attributes', [
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);
    });

    it ('returns a 422 error if the request has no product_id', function() {
        $user = User::factory()->create();
        $attribute = Attribute::factory()->create();

        $response = actingAs($user)->postJson('/api/product-attributes', [
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'product_id' => ['The product id field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no attribute_id', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = actingAs($user)->postJson('/api/product-attributes', [
            'product_id' => $product->id,
            'value' => 'some-value'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'attribute_id' => ['The attribute id field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no value', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create();

        $response = actingAs($user)->postJson('/api/product-attributes', [
            'product_id' => $product->id,
            'attribute_id' => $attribute->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'value' => ['The value field is required.'],
        ]);
    });
});

describe('Get Product Attribute', function() {
    it ('returns a product attribute if it exists', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create();

        $productAttribute = ProductAttribute::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);

        $response = actingAs($user)->getJson('/api/product-attributes/' . $productAttribute->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);
    });

    it ('returns a 404 error if the product attribute is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/product-attributes/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product attribute not found',
        ]);
    });
});

describe('Update Product Attribute', function() {
    it ('updates the product attribute if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create();

        $productAttribute = ProductAttribute::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);

        $response = actingAs($user)->putJson('/api/product-attributes/' . $productAttribute->id, [
            'value' => 'updated-value',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'updated-value'
        ]);
    });

    it ('returns a 404 error if the product attribute is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/product-attributes/999999', [
            'value' => 'updated-value',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product attribute not found',
        ]);
    });
});

describe('Delete Product Attribute', function() {
    it ('deletes a propduct attribute if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create();

        $productAttribute = ProductAttribute::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'some-value'
        ]);

        $response = actingAs($user)->deleteJson('/api/product-attributes/' . $productAttribute->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the product attribute is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/product-attributes/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product attribute not found',
        ]);
    });
});
