<?php

use App\Models\Product;
use App\Models\ProductImage;

use function Pest\Laravel\getJson;

describe('Get Product Images', function() {
    it('returns a list of product images', function() {
        $product = Product::factory()->create();
        ProductImage::factory(5)->create(['product_id' => $product->id]);

        $product2 = Product::factory()->create();
        ProductImage::factory(10)->create(['product_id' => $product2->id]);

        $response = getJson('/api/site/product-images');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
    });

    it('returns a list of product images owned by specific product', function() {
        $product = Product::factory()->create();
        ProductImage::factory(5)->create(['product_id' => $product->id]);

        $product2 = Product::factory()->create();
        ProductImage::factory(10)->create(['product_id' => $product2->id]);

        $response = getJson('/api/site/product-images?product_id=' . $product->id);

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    });
});
