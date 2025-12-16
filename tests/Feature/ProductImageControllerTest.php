<?php

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
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
        Storage::fake('s3');

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

        Storage::disk('s3')->assertExists(
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
        Storage::fake('s3'); // Fake the S3 disk
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Fake stored image
        $path = 'products/test-image.jpg';
        Storage::disk('s3')->put($path, 'fake-content');

        $image = ProductImage::create([
            'source' => $path,
            'product_id' => $product->id,
            'cover' => true,
        ]);

        // Assert file exists
        Storage::disk('s3')->assertExists($path);

        $response = actingAs($user)->deleteJson('/api/product-images/' . $image->id);

        $response->assertStatus(204);
        // Assert DB deletion
        assertDatabaseMissing('product_images', [
            'id' => $image->id,
        ]);

        // Assert file deletion
        Storage::disk('s3')->assertMissing($path);
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

describe('Set Product Image Cover', function() {
    it('updates the cover image of the product and set the rest of product image to non cover', function() {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $oldCoverImage = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => true]);
        $nonCoverImage1 = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => false]);
        $nonCoverImage2 = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => false]);
        $nonCoverImage3 = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => false]);
        $newCoverImage = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => false]);

        $response = actingAs($user)->patchJson('/api/product-images-cover/' . $newCoverImage->id);

        $response->assertStatus(200);
        assertDatabaseHas('product_images', ['id' => $oldCoverImage->id, 'product_id' => $product->id, 'cover' => false]);
        assertDatabaseHas('product_images', ['id' => $nonCoverImage1->id, 'product_id' => $product->id, 'cover' => false]);
        assertDatabaseHas('product_images', ['id' => $nonCoverImage2->id, 'product_id' => $product->id, 'cover' => false]);
        assertDatabaseHas('product_images', ['id' => $nonCoverImage3->id, 'product_id' => $product->id, 'cover' => false]);
        assertDatabaseHas('product_images', ['id' => $newCoverImage->id, 'product_id' => $product->id, 'cover' => true]);
    });

    it ('returns a 404 error if the product image is not found', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $response = actingAs($user)->patchJson('/api/product-images-cover/9999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Image not found',
        ]);
    });
});

describe("Update Product Image Cover", function() {
    it ("Sets the cover to true to the product image base on the product ID and set the rest to false", function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $image1 = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => true]);
        $image2 = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => false]);
        $image3 = ProductImage::factory()->create(['product_id' => $product->id, 'cover' => false]);

        $response = actingAs($user)->patchJson('/api/product-images-cover/' . $image2->id);

        $response->assertStatus(200);
        expect(ProductImage::find($image1->id)->cover)->toBe(0);
        expect(ProductImage::find($image2->id)->cover)->toBe(1);
        expect(ProductImage::find($image3->id)->cover)->toBe(0);
    });
});