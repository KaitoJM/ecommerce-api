<?php

namespace App\Services\Site\Order;

use App\Services\Site\Order\Contexts\AddOrderContext;
use App\Services\Site\Order\Pipes\CreateOrder\CalculateOrderTotalsPipe;
use App\Services\Site\Order\Pipes\CreateOrder\PrepareOrderItemsPipe;
use App\Services\Site\Order\Pipes\CreateOrder\PrepareOrderParametersPipe;
use App\Services\Site\Order\Pipes\CreateOrder\ProcessOrderCreationPipe;
use App\Services\Site\Order\Pipes\CreateOrder\ResolveOrderStatusPipe;
use Illuminate\Pipeline\Pipeline;

class OrderService {
    public function __construct(
        private Pipeline $pipeline
    ) {}

    public function createOrder($params, $items, $customerId = null) {
        $items = collect($items);
        $context = new AddOrderContext($params, $items, $customerId);

        return $this->pipeline
            ->send($context)
            ->through([
                PrepareOrderItemsPipe::class,
                CalculateOrderTotalsPipe::class,
                ResolveOrderStatusPipe::class,
                PrepareOrderParametersPipe::class,
                ProcessOrderCreationPipe::class
            ])
            ->thenReturn();
    }
}
