<?php

use App\Models\Brand;

use function Pest\Laravel\getJson;

describe('Get Brands', function() {
    it('returns a list of brands', function() {
        Brand::factory(10)->create();

        $response = getJson('/api/site/brands');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of brands with search', function() {
        Brand::create([
            'name' => 'Test Brand',
        ]);

        Brand::create([
            'name' => 'Sample Brand',
        ]);

        $response = getJson('/api/site/brands?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Brand',
        ]);
    });
});
