<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;

use function Pest\Laravel\getJson;

describe('Get Products', function() {
    it('returns a list of products', function() {
        Product::factory(10)->create(['published' => true]);

        $response = getJson('/api/site/products');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of products with search', function() {
        Product::create([
            'name' => 'Test Product',
            'published' => true
        ]);

        Product::create([
            'name' => 'Sample Product',
            'published' => true
        ]);

        $response = getJson('/api/site/products?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Product',
        ]);
    });

    it('returns a list of products filtered by categories', function() {
        [$category1, $category2, $category3] = Category::factory(3)->create();

        Product::create([
            'name' => 'Sample Product 1',
            'published' => true,
        ])->categories()->sync([$category1->id, $category3->id]);

        Product::create([
            'name' => 'Sample Product 2',
            'published' => true,
        ])->categories()->sync([$category2->id, $category3->id]);

        Product::create([
            'name' => 'Sample Product 3',
            'published' => true,
        ])->categories()->sync([$category1->id, $category2->id]);


        $response = getJson('/api/site/products?categories=' . $category2->id . ',' . $category3->id . '');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Sample Product 2',
        ]);
    });

    it('returns a list of products filtered by price range', function() {
        [$category1, $category2, $category3] = Category::factory(3)->create();

        $product1 = Product::create([
            'name' => 'Sample Product 1',
            'published' => true,
        ]);

        $product1->categories()->sync([$category1->id, $category3->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product1->id,
            'price' => 10000
        ]);

        $product2 = Product::create([
            'name' => 'Sample Product 2',
            'published' => true,
        ]);

        $product2->categories()->sync([$category2->id, $category3->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product2->id,
            'price' => 15000
        ]);

        $product3 = Product::create([
            'name' => 'Sample Product 3',
            'published' => true,
        ]);

        $product3->categories()->sync([$category1->id, $category2->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product3->id,
            'price' => 20000
        ]);


        $response = getJson('/api/site/products?price_min=10000&price_max=15000');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'name' => 'Sample Product 1',
        ]);
        $response->assertJsonFragment([
            'name' => 'Sample Product 2',
        ]);
    });

    it('returns a list of products filtered by minimum price', function() {
        [$category1, $category2, $category3] = Category::factory(3)->create();

        $product1 = Product::create([
            'name' => 'Sample Product 1',
            'published' => true,
        ]);

        $product1->categories()->sync([$category1->id, $category3->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product1->id,
            'price' => 10000
        ]);

        $product2 = Product::create([
            'name' => 'Sample Product 2',
            'published' => true,
        ]);

        $product2->categories()->sync([$category2->id, $category3->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product2->id,
            'price' => 15000
        ]);

        $product3 = Product::create([
            'name' => 'Sample Product 3',
            'published' => true,
        ]);

        $product3->categories()->sync([$category1->id, $category2->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product3->id,
            'price' => 20000
        ]);


        $response = getJson('/api/site/products?price_min=15000');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'name' => 'Sample Product 2',
        ]);
        $response->assertJsonFragment([
            'name' => 'Sample Product 3',
        ]);
    });

    it('returns a list of products filtered by maximum price', function() {
        [$category1, $category2, $category3] = Category::factory(3)->create();

        $product1 = Product::create([
            'name' => 'Sample Product 1',
            'published' => true,
        ]);

        $product1->categories()->sync([$category1->id, $category3->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product1->id,
            'price' => 10000
        ]);

        $product2 = Product::create([
            'name' => 'Sample Product 2',
            'published' => true,
        ]);

        $product2->categories()->sync([$category2->id, $category3->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product2->id,
            'price' => 15000
        ]);

        $product3 = Product::create([
            'name' => 'Sample Product 3',
            'published' => true,
        ]);

        $product3->categories()->sync([$category1->id, $category2->id]);

        ProductSpecification::factory()->create([
            'product_id' => $product3->id,
            'price' => 20000
        ]);


        $response = getJson('/api/site/products?price_max=15000');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'name' => 'Sample Product 1',
        ]);
        $response->assertJsonFragment([
            'name' => 'Sample Product 2',
        ]);
    });

    it('returns a list of products filtered by brands', function() {
        [$brand1, $brand2, $brand3] = Brand::factory(3)->create();

        Product::create([
            'name' => 'Sample Product 1',
            'published' => true,
            'brand_id' => $brand1->id
        ]);

        Product::create([
            'name' => 'Sample Product 2',
            'published' => true,
            'brand_id' => $brand2->id
        ]);

        Product::create([
            'name' => 'Sample Product 3',
            'published' => true,
            'brand_id' => $brand3->id
        ]);


        $response = getJson('/api/site/products?brands=' . $brand1->id . ',' . $brand3->id . '');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'name' => 'Sample Product 1',
        ]);
        $response->assertJsonFragment([
            'name' => 'Sample Product 3',
        ]);
    });
});
