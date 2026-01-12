<?php

namespace App\Services\Site\Order\Pipes\CreateOrder;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use App\Services\Site\Order\Contexts\AddOrderContext;
use App\Services\Site\OrderItem\OrderItemService as OrderItemOrderItemService;

class ProcessOrderCreationPipe
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderItemOrderItemService $orderItemService,
    ) {}

    public function handle(AddOrderContext $context, Closure $next)
    {
        $context->order = DB::transaction(function () use ($context) {
            $order = $this->orderRepository->createOrder($context->params);
            foreach ($context->itemsModelled as $item) {
                $this->orderItemService->createCartItem(
                    $order,
                    $item['product'],
                    $item['specification'],
                    $item['quantity']
                );
            }

            return $order;
        });

        return $next($context);
    }
}
