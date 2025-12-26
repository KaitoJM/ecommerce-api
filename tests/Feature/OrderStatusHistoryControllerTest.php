<?php

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\User;
use PHPUnit\Logging\OpenTestReporting\Status;

use function Pest\Laravel\actingAs;

describe('Get Order Status Histories', function() {
    it('returns a list of order status history', function() {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => Customer::factory()->create([
                'user_id' => User::factory()->create(['role' => 'customer'])
            ])
        ]);

        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'status_id' => OrderStatus::factory()->create()->id
        ]);

        OrderStatus::factory(5)->create();
        $statuses = OrderStatus::all();

        foreach ($statuses as $key => $status) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status_id' => $status->id,
                'user_id' => $user->id
            ]);
        }

        $response = actingAs($user)->getJson('/api/order-status-history?order_id=' . $order->id);

        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');
    });

    it('returns a 422 error if order_id is not provided', function() {
        $user = User::factory()->create();

        $response = actingAs($user)->getJson('/api/order-status-history');

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'order_id' => ['The order id field is required.'],
        ]);
    });
});

describe('Create Order Status History', function() {
    it('creates a new order status history if user is authenticated', function() {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => Customer::factory()->create([
                'user_id' => User::factory()->create(['role' => 'customer'])
            ])
        ]);

        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'status_id' => $status->id
        ]);


        $response = actingAs($user)->postJson('/api/order-status-history',[
            'order_id' => $order->id,
            'status_id' => $status->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'order_id' => $order->id,
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);
    });

    it('returns a 422 error if order_id is not provided', function() {
        $user = User::factory()->create();

        $response = actingAs($user)->postJson('/api/order-status-history');

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'order_id' => ['The order id field is required.'],
        ]);
    });

    it('returns a 422 error if status_id is not provided', function() {
        $user = User::factory()->create();

        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => Customer::factory()->create([
                'user_id' => User::factory()->create(['role' => 'customer'])
            ])
        ]);

        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'status_id' => $status->id
        ]);

        $response = actingAs($user)->postJson('/api/order-status-history', [
            'order_id' => $order->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status_id' => ['The status id field is required.'],
        ]);
    });
});

describe('Get Order Status History', function() {
    it ('returns an order status history if it exists', function() {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => Customer::factory()->create([
                'user_id' => User::factory()->create(['role' => 'customer'])
            ])
        ]);

        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'status_id' => $status->id
        ]);

        $orderStatusHistory = OrderStatusHistory::create([
            'order_id' => $order->id,
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $response = actingAs($user)->getJson('/api/order-status-history/' . $orderStatusHistory->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'order_id' => $order->id,
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);
    });

    it ('returns a 404 error if the order status history is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/order-status-history/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order history not found',
        ]);
    });
});

describe('Update Order Status History', function() {
    it ('updates the order status history if user is authenticated', function() {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => Customer::factory()->create([
                'user_id' => User::factory()->create(['role' => 'customer'])
            ])
        ]);

        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'status_id' => $status->id
        ]);

        $orderStatusHistory = OrderStatusHistory::create([
            'order_id' => $order->id,
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $newStatus = OrderStatus::factory()->create();

        $response = actingAs($user)->putJson('/api/order-status-history/' . $orderStatusHistory->id, [
            'status_id' => $newStatus->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'order_id' => $order->id,
            'status_id' => $newStatus->id,
            'user_id' => $user->id
        ]);
    });

    it ('returns a 404 error if the order status history is not found', function() {
        $user = User::factory()->create();
        $newStatus = OrderStatus::factory()->create();
        $response = actingAs($user)->putJson('/api/order-status-history/999999', [
            'status_id' => $newStatus->id,
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order history not found',
        ]);
    });
});

describe('Delete Order Status History', function() {
    it ('deletes the order status history if user is authenticated', function() {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => Customer::factory()->create([
                'user_id' => User::factory()->create(['role' => 'customer'])
            ])
        ]);

        $status = OrderStatus::factory()->create();
        $order = Order::factory()->create([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'status_id' => $status->id
        ]);

        $orderStatusHistory = OrderStatusHistory::create([
            'order_id' => $order->id,
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $response = actingAs($user)->deleteJson('/api/order-status-history/' . $orderStatusHistory->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the order status history is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/order-status-history/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Order history not found',
        ]);
    });
});
