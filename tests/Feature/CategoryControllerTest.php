<?php

use App\Models\Category;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Get Categories', function() {
    it('is accessible without authentication', function() {
        $response = getJson('/api/categories');

        $response->assertStatus(200);
    });

    it('returns a list of categories', function() {
        Category::factory(10)->create();

        $response = getJson('/api/categories');

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

        $response = getJson('/api/categories?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Category',
        ]);
    });
});

describe('Create Category', function() {
    it('accessible only if the user is authenticated', function() {
        $response = postJson('/api/categories', []);

        $response->assertStatus(401);
    });

    it ('creates a new categegory if user is authenticated', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/categories', [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Test Category',
        ]);
    });

    it ('returns a 422 error if the request has no name', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/categories', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'name' => ['The name field is required.'],
        ]);
    });
});

describe('Get Category', function() {
    it ('returns a category if it exists', function() {
        $category = Category::create([
            'name' => 'Test Category',
        ]);

        $response = getJson('/api/categories/' . $category->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test Category',
        ]);
    });

    it ('returns a 404 error if the category is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/categories/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Category not found',
        ]);
    });
});

describe('Update Category', function() {
    it ('updates a category if user is authenticated', function() {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Test Category',
        ]);

        $response = actingAs($user)->putJson('/api/categories/' . $category->id, [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated Category',
        ]);
    });

    it ('returns a 404 error if the category is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/categories/999999', [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Category not found',
        ]);
    });
});

describe('Delete Category', function() {
    it ('deletes a category if user is authenticated', function() {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Test Category',
        ]);
        $response = actingAs($user)->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the category is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/categories/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Category not found',
        ]);
    });
});
