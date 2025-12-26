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

    it('returns a 422 error if order_is is not provided', function() {
        $user = User::factory()->create();

        $response = actingAs($user)->getJson('/api/order-status-history');

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'order_id' => ['The order id field is required.'],
        ]);
    });
});
