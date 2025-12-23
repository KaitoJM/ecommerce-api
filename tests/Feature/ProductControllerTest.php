<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Get Products', function() {
    it('is accessible without authentication', function() {
        $response = getJson('/api/products');

        $response->assertStatus(200);
    });

    it('returns a list of products', function() {
        Product::factory(10)->create();

        $response = getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of products with search', function() {
        Product::create([
            'name' => 'Test Product',
        ]);

        Product::create([
            'name' => 'Sample Product',
        ]);

        $response = getJson('/api/products?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Product',
        ]);
    });

    it('returns a list of products with filters', function() {
        Product::create([
            'name' => 'Sample Product 1',
            'published' => true,
        ]);

        Product::create([
            'name' => 'Sample Product 2',
            'published' => false,
        ]);

        Product::create([
            'name' => 'Sample Product 3',
            'published' => true,
        ]);

        Product::create([
            'name' => 'Sample Product 4',
            'published' => false,
        ]);

        $response = getJson('/api/products?published=1');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'name' => 'Sample Product 3',
        ]);
    });
});

describe('Create Product', function() {
    it('accessible only if the user is authenticated', function() {
        $response = postJson('/api/products', []);

        $response->assertStatus(401);
    });

    it ('creates a new product if user is authenticated', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/products', [
            'name' => 'Test Product',
            'summary' => 'Test Description',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Test Product',
            'summary' => 'Test Description',
        ]);
    });

    it('syncs categories when provided', function() {
        $category1 = Category::create(['name' => 'Category 1']);
        $category2 = Category::create(['name' => 'Category 2']);
        $category3 = Category::create(['name' => 'Category 3']);

        $user = User::factory()->create();

        $response = actingAs($user)->postJson('/api/products', [
            'name' => 'Test Product',
            'summary' => 'Test Description',
            'categories' => [$category1->id,$category2->id,$category3->id]
        ]);

        $productId = $response->json('data.id');

        $response->assertStatus(201);

        assertDatabaseHas('product_categories', ['product_id' => $productId, 'category_id' => $category1->id]);
        assertDatabaseHas('product_categories', ['product_id' => $productId, 'category_id' => $category2->id]);
        assertDatabaseHas('product_categories', ['product_id' => $productId, 'category_id' => $category3->id]);

    });

    it ('returns a 422 error if the request has no name', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/products', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'name' => ['The name field is required.'],
        ]);
    });
});

describe('Get Product', function() {
    it ('returns a product if it exists', function() {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);

        $response = getJson('/api/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);
    });

    it ('returns a 404 error if the product is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/products/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product not found',
        ]);
    });
});

describe('Update Product', function() {
    it ('updates a product if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Test Product',
            'summary' => 'Test Description',
        ]);

        $response = actingAs($user)->putJson('/api/products/' . $product->id, [
            'name' => 'Updated Product',
            'summary' => 'Updated Description',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated Product',
            'summary' => 'Updated Description',
        ]);
    });

    it('syncs categories when provided', function() {
        $category1 = Category::create(['name' => 'Category 1']);
        $category2 = Category::create(['name' => 'Category 2']);
        $category3 = Category::create(['name' => 'Category 3']);

        $product = Product::create([
            'name' => 'Test Product',
            'summary' => 'Test Description',
        ]);

        $product->categories()->attach([$category1->id, $category2->id]);

        $user = User::factory()->create();

        $response = actingAs($user)->putJson('/api/products/' . $product->id, [
            'name' => 'Updated Product',
            'summary' => 'Updated Description',
            'categories' => [$category1->id, $category3->id]
        ]);

        $productId = $product->id;

        $response->assertStatus(200);

        assertDatabaseHas('product_categories', ['product_id' => $productId, 'category_id' => $category1->id]);
        assertDatabaseMissing('product_categories', ['product_id' => $productId, 'category_id' => $category2->id]);
        assertDatabaseHas('product_categories', ['product_id' => $productId, 'category_id' => $category3->id]);

    });

    it ('returns a 404 error if the product is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/products/999999', [
            'name' => 'Updated Product',
            'summary' => 'Updated Description',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product not found',
        ]);
    });
});

describe('Delete Product', function() {
    it ('deletes a product if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);
        $response = actingAs($user)->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the product is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/products/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product not found',
        ]);
    });
});
