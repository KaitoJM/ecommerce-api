<?php

namespace App\Services\Site\OrderItem;

use App\Models\CartItem;
use App\Models\Order;
use App\Repositories\OrderItemRepository;
use App\Services\Site\OrderItem\Contexts\AddOrderItemFromCartContext;
use App\Services\Site\OrderItem\Validation\CheckAndUpdateStockAvailability;
use Illuminate\Pipeline\Pipeline;

class OrderItemService {
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        private Pipeline $pipeline
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

        $context = new AddOrderItemFromCartContext($item->product_specification_id, $item->quantity);

        $this->pipeline
            ->send($context)
            ->through([
                CheckAndUpdateStockAvailability::class
            ])
            ->thenReturn();

        $order = $this->orderItemRepository->createOrderItem($params);
    }
}
