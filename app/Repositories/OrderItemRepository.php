<?php

namespace App\Repositories;

use App\Models\OrderItem;
use Carbon\Carbon;

class OrderItemRepository {
    public function getOrderItems(?string $search = null, $filters = null, $pagination = null) {
        return OrderItem::search($search)
        ->filterProductId($filters['product_id'] ?? null)
        ->filterOrderId($filters['order_id'] ?? null)
        ->filterProductSpecificationId($filters['product_specification_id'] ?? null)
        ->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     order_id: string,
     *     product_id: string,
     *     product_specification_id: string,
     *     product_snapshot_name: string,
     *     product_snapshot_price: float,
     *     quantity: integer,
     *     total: float,
     * }  $params
     * @return \App\Models\OrderItem
     */
    public function createOrderItem($params) {
        $createdOrderItem = OrderItem::create([
            'order_id' => $params['order_id'],
            'product_id' => $params['product_id'],
            'product_specification_id' => $params['product_specification_id'],
            'product_snapshot_name' => $params['product_snapshot_name'],
            'product_snapshot_price' => $params['product_snapshot_price'],
            'quantity' => $params['quantity'],
            'total' => $params['total'] ?? 0,
        ]);

        return $createdOrderItem;
    }

    /**
     * Get an order item by its ID.
     *
     * @param int $id The ID of the order item to get
     * @return \App\Models\OrderItem
     */
    public function getOrderItemById(int $id) {
        return OrderItem::with(['product', 'product_specification'])
        ->findOrFail($id);
    }

    /**
     * Update an order item by its ID.
     *
     * @param int $id The ID of the order item to update
     * @param array $params The parameters to update the order item with
     * @return \App\Models\OrderItem
     */
    public function updateOrderItem(int $id, array $params) {
        $orderItem = $this->getOrderItemById($id);

        $orderItem->update($params);

        return $orderItem;
    }

    /**
     * Delete an order item by its ID.
     *
     * @param int $id The ID of the order item to delete
     * @return \App\Models\OrderItem
     */
    public function deleteOrderItem(int $id) {
        $orderItem = $this->getOrderItemById($id);

        $orderItem->delete();

        return $orderItem;
    }
}
