<?php

use App\Models\Brand;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Get Brands', function() {
    it('returns a list of brands', function() {
        $user = User::factory()->create();
        Brand::factory(10)->create();

        $response = actingAs($user)->getJson('/api/brands');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of brands with search', function() {
        $user = User::factory()->create();

        Brand::create([
            'name' => 'Test Brand',
        ]);

        Brand::create([
            'name' => 'Sample Brand',
        ]);

        $response = actingAs($user)->getJson('/api/brands?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Brand',
        ]);
    });
});

describe('Create Brand', function() {
    it('accessible only if the user is authenticated', function() {
        $response = postJson('/api/brands', []);

        $response->assertStatus(401);
    });

    it ('creates a new brand if user is authenticated', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/brands', [
            'name' => 'Test Brand',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Test Brand',
        ]);
    });

    it ('returns a 422 error if the request has no name', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/brands', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'name' => ['The name field is required.'],
        ]);
    });
});

describe('Get Brand', function() {
    it ('returns a brand if it exists', function() {
        $user = User::factory()->create();

        $brand = Brand::create([
            'name' => 'Test Brand',
        ]);

        $response = actingAs($user)->getJson('/api/brands/' . $brand->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test Brand',
        ]);
    });

    it ('returns a 404 error if the brand is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/brands/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Brand not found',
        ]);
    });
});

describe('Update Brand', function() {
    it ('updates a brand if user is authenticated', function() {
        $user = User::factory()->create();
        $brand = Brand::create([
            'name' => 'Test Brand',
        ]);

        $response = actingAs($user)->putJson('/api/brands/' . $brand->id, [
            'name' => 'Updated Brand',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated Brand',
        ]);
    });

    it ('returns a 404 error if the brand is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/brands/999999', [
            'name' => 'Updated Brand',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Brand not found',
        ]);
    });
});

describe('Delete Brand', function() {
    it ('deletes a brand if user is authenticated', function() {
        $user = User::factory()->create();
        $brand = Brand::create([
            'name' => 'Test brand',
        ]);
        $response = actingAs($user)->deleteJson('/api/brands/' . $brand->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the brand is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/brands/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Brand not found',
        ]);
    });
});
