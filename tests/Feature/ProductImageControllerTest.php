<?php

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;

describe('Get Product Images', function() {
    it('is accessible without authentication', function() {
        $response = getJson('/api/product-images');

        $response->assertStatus(200);
    });

    it('returns a list of product images', function() {
        $product = Product::factory()->create();
        ProductImage::factory(5)->create(['product_id' => $product->id]);

        $product2 = Product::factory()->create();
        ProductImage::factory(10)->create(['product_id' => $product2->id]);

        $response = getJson('/api/product-images');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
    });

    it('returns a list of product images owned by specific product', function() {
        $product = Product::factory()->create();
        ProductImage::factory(5)->create(['product_id' => $product->id]);

        $product2 = Product::factory()->create();
        ProductImage::factory(10)->create(['product_id' => $product2->id]);

        $response = getJson('/api/product-images?product_id=' . $product->id);

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    });
});

describe("Create Product Image", function() {
    it ("uploads product image if user is authenticated", function() {
        Storage::fake('local');

        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Fake image
        $file = UploadedFile::fake()->image('sample.jpg');

        $response = actingAs($user)->postJson('/api/product-images', [
            'product_id' => $product->id,
            'image' => $file,
            'cover' => 1,
        ]);

        $response->assertStatus(201);

        Storage::disk('local')->assertExists(
            'product-images/' . $file->hashName()
        );

        assertDatabaseHas('product_images', [
            'product_id' => $product->id, 
            'cover' => 1
        ]);
    });
});

describe('Get Product Image', function() {
    it ('returns a product image if it exists', function() {
        $product = Product::factory()->create();
        $productImage = ProductImage::create([
            'source' => 'test-source',
            'product_id' => $product->id,
            'cover' => true,
        ]);

        $response = getJson('/api/product-images/' . $productImage->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'source' => 'test-source',
            'product_id' => $product->id,
            'cover' => 1,
        ]);
    });

    it ('returns a 404 error if the product image is not found', function() {
        $response = getJson('/api/product-images/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Image not found',
        ]);
    });
});

describe('Delete Product Image', function() {
    it ('deletes a product image if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $image = ProductImage::create([
            'source' => 'test-source',
            'product_id' => $product->id,
            'cover' => true,
        ]);

        $response = actingAs($user)->deleteJson('/api/product-images/' . $image->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the product image is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/product-images/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Image not found',
        ]);
    });
});