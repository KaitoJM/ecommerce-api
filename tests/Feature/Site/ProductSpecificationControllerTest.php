<?php

use App\Models\Product;
use App\Models\ProductSpecification;

use function Pest\Laravel\getJson;

describe('Get Product Specifications', function() {
    it ('returns a product specification data when user is authenticated', function() {
        $product = Product::factory()->create();

        ProductSpecification::factory(10)->create([
            'product_id' => $product->id,
            'combination' => '1,2,3'
        ]);

        $response = getJson('/api/site/product-specifications');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it ('returns a product specification data with filter', function() {
        $product = Product::factory()->create();
        $product2 = Product::factory()->create();

        ProductSpecification::factory(10)->create([
            'product_id' => $product->id,
            'combination' => '1,2,3',
            'default' => 0
        ]);

        ProductSpecification::factory()->create([
            'product_id' => $product->id,
            'combination' => '1,2,3',
            'default' => 1
        ]);

        ProductSpecification::factory(5)->create([
            'product_id' => $product2->id,
            'combination' => '1,2,3'
        ]);

        $response = getJson('/api/site/product-specifications?product_id=' . $product->id . '&default=' . 1);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    });
});
