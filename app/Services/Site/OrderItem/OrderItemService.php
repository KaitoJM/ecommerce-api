<?php

namespace App\Services\Site\OrderItem;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Repositories\OrderItemRepository;
use App\Services\Site\OrderItem\Contexts\AddOrderItemContext;
use App\Services\Site\OrderItem\Pipes\CreateOrderItem\CheckAndUpdateStockAvailabilityPipe;
use App\Services\Site\OrderItem\Pipes\CreateOrderItem\PrepareOrderItemParamsPipe;
use App\Services\Site\OrderItem\Pipes\CreateOrderItem\UpdateProductSpecificationPipe;
use Illuminate\Pipeline\Pipeline;

class OrderItemService {
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        private Pipeline $pipeline
    ) {}

    public function createCartItem(Order $order, Product $product, ProductSpecification $specification, int $quantity) {
        $context = new AddOrderItemContext($order, $product, $specification, $quantity);

        $this->pipeline
            ->send($context)
            ->through([
                PrepareOrderItemParamsPipe::class,
                CheckAndUpdateStockAvailabilityPipe::class,
                UpdateProductSpecificationPipe::class
            ])
            ->thenReturn();

        $order = $this->orderItemRepository->createOrderItem($context->params);
    }
}
