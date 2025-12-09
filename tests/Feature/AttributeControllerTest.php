<?php

use App\Models\Attribute;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('Get Attributes', function() {
    it('is accessible if authenticated', function() {
        $response = getJson('/api/attributes');

        $response->assertStatus(401);
    });

    it('returns a list of attributes', function() {
        Attribute::factory(10)->create();
        $user = User::factory()->create();

        $response = actingAs($user)->getJson('/api/attributes');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of attributes with search', function() {
        $user = User::factory()->create();

        Attribute::create([
            'attribute' => 'Test Attribute',
        ]);

        Attribute::create([
            'attribute' => 'Sample Attribute',
        ]);

        $response = actingAs($user)->getJson('/api/attributes?search=Test');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'attribute' => 'Test Attribute',
        ]);
    });
});

describe('Create Attributed', function() {
    it('accessible only if the user is authenticated', function() {
        $response = postJson('/api/attributes', []);

        $response->assertStatus(401);
    });

    it ('creates a new attribute if user is authenticated', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/attributes', [
            'attribute' => 'Test Attribute',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'attribute' => 'Test Attribute',
        ]);
    });

    it ('returns a 422 error if the request has no attribute name', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/attributes', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'attribute' => ['The attribute field is required.'],
        ]);
    });
});

describe('Get Attribute', function() {
    it ('returns a attribute if it exists', function() {
        $user = User::factory()->create();

        $attribute = Attribute::create([
            'attribute' => 'Test Attribute',
        ]);

        $response = actingAs($user)->getJson('/api/attributes/' . $attribute->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'attribute' => 'Test Attribute',
        ]);
    });

    it ('returns a 404 error if the attribute is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/attributes/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Attribute not found',
        ]);
    });
});

describe('Update Attribute', function() {
    it ('updates a attribute if user is authenticated', function() {
        $user = User::factory()->create();
        $attribute = Attribute::create([
            'attribute' => 'Test Attribute',
        ]);

        $response = actingAs($user)->putJson('/api/attributes/' . $attribute->id, [
            'attribute' => 'Updated Attribute',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'attribute' => 'Updated Attribute',
        ]);
    });

    it ('returns a 404 error if the attribute is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/attributes/999999', [
            'attribute' => 'Updated Attribute',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Attribute not found',
        ]);
    });
});

describe('Delete Attribute', function() {
    it ('deletes a attribute if user is authenticated', function() {
        $user = User::factory()->create();
        $attribute = Attribute::create([
            'attribute' => 'Test Attribute',
        ]);
        $response = actingAs($user)->deleteJson('/api/attributes/' . $attribute->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the attribute is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/attributes/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Attribute not found',
        ]);
    });
});
