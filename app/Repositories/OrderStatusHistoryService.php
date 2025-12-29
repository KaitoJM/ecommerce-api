<?php

namespace App\Repositories;

use App\Models\OrderStatusHistory;

class OrderStatusHistoryService {
    public function getOrderStatusHistories($order_id = null) {
        return OrderStatusHistory::with('status')
        ->when($order_id, function($query) use ($order_id){
            $query->where('order_id', $order_id);
        })->get();
    }

    /**
     * Save new order status history value
     * @param int $order_id The order the status value will be belonged to
     * @param int $status_id The order status the history value will be belonged to
     * @param int $user_id The user that initiated the action
     * @return \App\Models\OrderStatusHistory
     */
    public function createOrderStatusHistory($order_id, $status_id, $user_id,) {
        $createdOrderStatusHistory = OrderStatusHistory::create([
            'order_id' => $order_id,
            'status_id' => $status_id,
            'user_id' => $user_id,
        ]);

        return $createdOrderStatusHistory;
    }

    /**
     * Get a order status history by its ID.
     *
     * @param int $id The ID of the order the status history to get
     * @return \App\Models\OrderStatusHistory
     */
    public function getOrderStatusHistoryById(int $id) {
        return OrderStatusHistory::findOrFail($id);
    }

    /**
     * Update an order status history by its ID.
     *
     * @param int $id The ID of the order history to update
     * @param array $params The parameters to update the order history with
     * @return \App\Models\OrderStatusHistory
     */
    public function updateOrderStatusHistory(int $id, $params) {
        $orderHistory = $this->getOrderStatusHistoryById($id);

        $orderHistory->update($params);

        return $orderHistory;
    }

    /**
     * Delete an order status history by its ID.
     *
     * @param int $id The ID of the order status history to delete
     * @return \App\Models\OrderStatusHistory
     */
    public function deleteOrderStatusHistory(int $id) {
        $OrderStatusHistory = $this->getOrderStatusHistoryById($id);

        $OrderStatusHistory->delete();

        return $OrderStatusHistory;
    }
}
