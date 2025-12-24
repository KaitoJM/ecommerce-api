<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class OrderService {
    public function getOrders(?string $search = null, $filters = null, $pagination = null) {
        return Order::when(isset($filters['customer_id']), function($query) use ($filters) {
            $query->where('customer_id', $filters['customer_id']);
        })->when(isset($filters['session_id']), function($query) use ($filters) {
            $query->where('session_id', $filters['session_id']);
        })->when(isset($filters['cart_id']), function($query) use ($filters) {
            $query->where('cart_id', $filters['cart_id']);
        })->when(isset($filters['status_id']), function($query) use ($filters) {
            $query->where('status_id', $filters['status_id']);
        })->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     customer_id: string,
     *     seesion_id: string,
     *     cart_id: string,
     *     status_id: string,
     *     subtotal: float,
     *     discount_total: float,
     *     tax_total: float,
     *     total: float,
     * }  $params
     * @return \App\Models\Order
     */
    public function createOrder($params) {
        $createdOrder = Order::create([
            'customer_id' => $params['customer_id'] ?? null,
            'session_id' => $params['session_id'] ?? null,
            'cart_id' => $params['cart_id'],
            'status_id' => $params['status_id'],
            'subtotal' => $params['subtotal'] ?? 0,
            'discount_total' => $params['discount_total'] ?? 0,
            'tax_total' => $params['tax_total'] ?? 0,
            'total' => $params['total'] ?? 0,
        ]);

        return $createdOrder;
    }

    /**
     * Get a order by its ID.
     *
     * @param int $id The ID of the order to get
     * @return \App\Models\Order
     */
    public function getOrderById(int $id) {
        return Order::findOrFail($id);
    }

    /**
     * Update an order by its ID.
     *
     * @param int $id The ID of the order to update
     * @param array $params The parameters to update the order with
     * @return \App\Models\Order
     */
    public function updateOrder(int $id, array $params) {
        $order = $this->getOrderById($id);

        $order->update($params);

        return $order;
    }

    /**
     * Delete an order by its ID.
     *
     * @param int $id The ID of the order to delete
     * @return \App\Models\Order
     */
    public function deleteOrder(int $id) {
        $order = $this->getOrderById($id);

        $order->delete();

        return $order;
    }
}
