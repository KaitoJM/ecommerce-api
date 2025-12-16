<?php

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductSpecification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

describe('Get Product Specifications', function() {
    it('is not accessible if user is not authenticated', function() {
        $response = getJson('/api/product-specifications');

        $response->assertStatus(401);
    });

    it ('returns a product specification data when user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        ProductSpecification::factory(10)->create([
            'product_id' => $product->id,
            'combination' => '1,2,3'
        ]);

        $response = actingAs($user)->getJson('/api/product-specifications');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it ('returns a product specification data with filter', function() {
        $user = User::factory()->create();
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

        $response = actingAs($user)->getJson('/api/product-specifications?product_id=' . $product->id . '&default=' . 1);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    });
});

describe('Create Product Specification', function() {
    it('is not accessible if user is not authenticated', function() {
        $response = postJson('/api/product-specifications', []);

        $response->assertStatus(401);
    });

    it('creates a product specification data with valid inputs', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $attr1 = Attribute::factory()->create();
        $attr2 = Attribute::factory()->create();

        $prodAttr1 = ProductAttribute::factory()->create(
            ['product_id' => $product->id, 'attribute_id' => $attr1->id]
        );

        $prodAttr2 = ProductAttribute::factory()->create(
            ['product_id' => $product->id, 'attribute_id' => $attr2->id]
        );

        $response = actingAs($user)->postJson('/api/product-specifications', [
            'product_id' => $product->id,
            'combination' => implode(',', [$prodAttr1->id, $prodAttr2->id]),
            'price' => 1000,
            'stock' => 10,
            'default' => true
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'combination' => implode(',', [$prodAttr1->id, $prodAttr2->id]),
            'price' => 1000,
            'stock' => 10,
            'default' => true
        ]);
    });

    it('returns a 422 error when no product ID specified', function() {
        $user = User::factory()->create();

        $response = actingAs($user)->postJson('/api/product-specifications', [
            'combination' => '1,2,3',
            'price' => 1000,
            'stock' => 10,
            'default' => true
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'product_id' => ['The product id field is required.'],
        ]);
    });
});

describe('Get Product Specification', function() {
    it('is not accessible if user is not authenticated', function() {
        $response = getJson('/api/product-specifications/1');

        $response->assertStatus(401);
    });

    it('retrieves the specific data of product specification by ID', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        ProductSpecification::factory(10)->create([
            'product_id' => $product->id,
            'combination' => '1,2,3'
        ]);

        $response = actingAs($user)->getJson('/api/product-specifications/1');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'combination' => '1,2,3'
        ]);
    });

    it ('returns a 404 error if the product is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/product-specifications/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product specification not found',
        ]);
    });
});

describe('Update Product Specification', function() {
    it('is not accessible if user is not authenticated', function() {
        $response = patchJson('/api/product-specifications/1', []);

        $response->assertStatus(401);
    });

    it ('updates a product specification if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $attr1 = Attribute::factory()->create();
        $attr2 = Attribute::factory()->create();

        $prodAttr1 = ProductAttribute::factory()->create(
            ['product_id' => $product->id, 'attribute_id' => $attr1->id]
        );

        $prodAttr2 = ProductAttribute::factory()->create(
            ['product_id' => $product->id, 'attribute_id' => $attr2->id]
        );


        $specification = ProductSpecification::create([
            'product_id' => $product->id,
            'combination' => '1,2,3',
            'price' => 1000,
            'stock' => 10,
            'default' => true
        ]);

        $response = actingAs($user)->patchJson('/api/product-specifications/' . $specification->id, [
            'product_id' => $product->id,
            'combination' => implode(',', [$prodAttr1->id, $prodAttr2->id]),
            'price' => 2000,
            'stock' => 8,
            'default' => false
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'combination' => implode(',', [$prodAttr1->id, $prodAttr2->id]),
            'price' => 2000,
            'stock' => 8,
            'default' => false
        ]);
    });

    it('returns a 422 error if product id is missing', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/product-specifications/999999', [
            'combination' => '1,2,3',
            'price' => 2000,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'product_id' => ['The product id field is required.'],
        ]);
    });

    it ('returns a 404 error if the product specification is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/product-specifications/999999', [
            'product_id' => '1',
            'price' => 2000,
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product specification not found',
        ]);
    });
});

describe('Delete Product Specification', function() {
    it('is not accessible if user is not authenticated', function() {
        $response = deleteJson('/api/product-specifications/1', []);

        $response->assertStatus(401);
    });

    it ('deletes a product specification if user is authenticated', function() {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $specification = ProductSpecification::create([
            'product_id' => $product->id,
            'combination' => '1,2,3',
            'price' => 1000,
            'stock' => 10,
            'default' => true
        ]);

        $response = actingAs($user)->deleteJson('/api/product-specifications/' . $specification->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the product specification is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/product-specifications/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Product specification not found',
        ]);
    });
});

describe("Delete all specifications of a product", function () {
    it("Deletes all specifications of a selected product only", function() {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        ProductSpecification::factory(10)->create([
            'product_id' => $product->id,
            'combination' => '1,2,3'
        ]);

        $product2 = Product::factory()->create();
        ProductSpecification::factory(10)->create([
            'product_id' => $product2->id,
            'combination' => '1,2,3'
        ]);

        expect(DB::table('product_specifications')->count())->toBe(20);

        $response = actingAs($user)->deleteJson('/api/product-specifications-delete-by-product/' . $product->id);

        $response->assertStatus(204);
        expect(
            ProductSpecification::where('product_id', $product->id)->count()
        )->toBe(0);
        expect(
            ProductSpecification::where('product_id', $product2->id)->count()
        )->toBe(10);
    });

});