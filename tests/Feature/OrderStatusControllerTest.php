<?php

use App\Models\OrderStatus;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Get Order Statuses', function() {
    it('returns a list of order statuses', function() {
        $user = User::factory()->create();

        OrderStatus::factory(10)->create();

        $response = actingAs($user)->getJson('/api/order-statuses');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });
});

describe('Create Order Status', function() {
    it ('creates a new order status if user is authenticated', function() {
        $user = User::factory()->create();

        $response = actingAs($user)->postJson('/api/order-statuses', [
            'status' => 'Pending',
            'color_code' => 'green',
            'description' => 'some-description',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'status' => 'Pending',
            'color_code' => 'green',
            'description' => 'some-description',
        ]);
    });
});

describe('Get Order Status', function() {
    it ('returns an order status if it exists', function() {
        $user = User::factory()->create();

        $status = OrderStatus::factory()->create([
            'status' => 'Sample Status'
        ]);

        $response = actingAs($user)->getJson('/api/order-statuses/' . $status->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $status->id,
            'status' => 'Sample Status'
        ]);
    });

    it ('returns a 404 error if the order status is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/order-statuses/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order status not found',
        ]);
    });
});

describe('Update Order Status', function() {
    it ('updates an order status if user is authenticated', function() {
        $user = User::factory()->create();

        $status = OrderStatus::factory()->create([
            'status' => 'Sample Status',
            'color_code' => 'old_color_code'
        ]);

        $response = actingAs($user)->putJson('/api/order-statuses/' . $status->id, [
            'color_code' => 'new_color_code',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $status->id,
            'status' => 'Sample Status',
            'color_code' => 'new_color_code',
        ]);
    });

    it ('returns a 404 error if the order status is not found', function() {
        $user = User::factory()->create();
        $status2 = OrderStatus::factory()->create();
        $response = actingAs($user)->putJson('/api/order-statuses/999999', [
            'color_code' => 'new_color_code',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order status not found',
        ]);
    });
});

describe('Delete Order Status', function() {
    it ('deletes an order status if user is authenticated', function() {
        $user = User::factory()->create();

       $status = OrderStatus::factory()->create([
            'status' => 'Sample Status',
            'color_code' => 'old_color_code'
        ]);

        $response = actingAs($user)->deleteJson('/api/order-statuses/' . $status->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the order status is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/order-statuses/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order status not found',
        ]);
    });
});
