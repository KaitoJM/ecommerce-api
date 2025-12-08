<?php

use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('Get Products', function() {
    it('accessible only if the user is authenticated', function() {
        $response = getJson('/api/products');

        $response->assertStatus(401);
    });

    it('returns a list of products', function() {
        $user = User::factory()->create();
        Product::factory(10)->create();

        $response = actingAs($user)->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of products with search', function() {
        $user = User::factory()->create();
        Product::create([
            'name' => 'Test Product',
        ]);

        Product::create([
            'name' => 'Sample Product',
        ]);

        $response = actingAs($user)->getJson('/api/products?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Product',
        ]);
    });

});
