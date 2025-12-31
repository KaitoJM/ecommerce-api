<?php

use App\Models\Category;

use function Pest\Laravel\getJson;

describe('Get Categories', function() {
    it('returns a list of categories', function() {
        Category::factory(10)->create();

        $response = getJson('/api/site/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of categories with search', function() {
        Category::create([
            'name' => 'Test Category',
        ]);

        Category::create([
            'name' => 'Sample Category',
        ]);

        $response = getJson('/api/site/categories?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Category',
        ]);
    });
});
