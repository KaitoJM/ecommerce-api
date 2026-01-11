<?php

namespace App\Services\Site\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Services\Site\Cart\Pipelines\AddCartPipeline;

class OrderItemService {
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
    ) {}

    public function createFromCartItem(Order $order, CartItem $item) {
        $total = $item->specification->price * $item->quantity;

        $params = [
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'product_specification_id' => $item->product_specification_id,
            'product_snapshot_name' => $item->product->name,
            'product_snapshot_price' => $item->specification->price,
            'quantity' => $item->quantity,
            'total' => $total,
        ];

        // validate stock
        // validate product specification ownership to product

        $order = $this->orderItemRepository->createOrderItem($params);
    }
}
