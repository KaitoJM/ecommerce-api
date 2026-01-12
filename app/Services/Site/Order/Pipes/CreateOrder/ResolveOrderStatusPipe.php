<?php

namespace App\Services\Site\Order\Pipes\CreateOrder;

use Closure;
use App\Repositories\OrderStatusRepository;
use App\Services\Site\Order\Contexts\AddOrderContext;

class ResolveOrderStatusPipe
{
    public function __construct(
        private OrderStatusRepository $statusRepository
    ) {}

    public function handle(AddOrderContext $context, Closure $next)
    {
        $status = $this->statusRepository->getOrderStatuses('Pending')->first();
        $context->status_id = $status->id;

        return $next($context);
    }
}
