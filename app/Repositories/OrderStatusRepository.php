<?php

namespace App\Repositories;

use App\Models\OrderStatus;

class OrderStatusRepository {
    public function getOrderStatuses(?string $search = null, $filters = null, $pagination = null) {
        return OrderStatus::when($search, function($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%");
            });
        })->when(isset($filters['color_code']), function($query) use ($filters) {
            $query->where('color_code', $filters['color_code']);
        })->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     status: string
     *     color_code: string
     *     password: string
     * }  $description
     * @return \App\Models\OrderStatus
     */
    public function createOrderStatus($params) {
        $createdOrderStatus = OrderStatus::create([
            'status' => $params['status'],
            'color_code' => $params['color_code'],
            'description' => $params['description'] ?? '',
        ]);

        return $createdOrderStatus;
    }

    /**
     * Get a order status by its ID.
     *
     * @param int $id The ID of the order status to get
     * @return \App\Models\OrderStatus
     */
    public function getOrderStatusById(int $id) {
        return OrderStatus::findOrFail($id);
    }

    /**
     * Update a order status by its ID.
     *
     * @param int $id The ID of the order status to update
     * @param array $params The parameters to update the order status with
     * @return \App\Models\OrderStatus
     */
    public function updateOrderStatus(int $id, array $params) {
        $orderStatus = $this->getOrderStatusById($id);

        $orderStatus->update($params);

        return $orderStatus;
    }

    /**
     * Delete an order status by its ID.
     *
     * @param int $id The ID of the order status to delete
     * @return \App\Models\OrderStatus
     */
    public function deleteOrderStatus(int $id) {
        $oderStatus = $this->getOrderStatusById($id);

        $oderStatus->delete();

        return $oderStatus;
    }
}
