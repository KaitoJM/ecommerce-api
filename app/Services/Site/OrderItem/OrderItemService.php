<?php

namespace App\Services\Site\OrderItem;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Repositories\OrderItemRepository;
use App\Services\Site\OrderItem\Contexts\AddOrderItemFromCartContext;
use App\Services\Site\OrderItem\Validation\CheckAndUpdateStockAvailability;
use Illuminate\Pipeline\Pipeline;

class OrderItemService {
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        private Pipeline $pipeline
    ) {}

    public function createCartItem(Order $order, Product $product, ProductSpecification $specification, int $quantity) {
        $total = $specification->price * $quantity;

        $params = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_specification_id' => $specification->id,
            'product_snapshot_name' => $product->name,
            'product_snapshot_price' => $specification->price,
            'quantity' => $quantity,
            'total' => $total,
        ];

        $context = new AddOrderItemFromCartContext($specification->id, $quantity);

        $this->pipeline
            ->send($context)
            ->through([
                CheckAndUpdateStockAvailability::class
            ])
            ->thenReturn();

        $order = $this->orderItemRepository->createOrderItem($params);
    }
}
