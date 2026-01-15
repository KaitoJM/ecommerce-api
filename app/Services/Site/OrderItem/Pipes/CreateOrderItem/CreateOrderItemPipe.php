<?php
namespace App\Services\Site\OrderItem\Pipes\CreateOrderItem;

use App\Repositories\OrderItemRepository;
use Closure;
use App\Services\Site\OrderItem\Contexts\AddOrderItemContext;

class CreateOrderItemPipe
{
    public function __construct(
        private OrderItemRepository $repository
    ) {}

    public function handle(AddOrderItemContext $context, Closure $next) {
        $context->createdOrderItem = $this->repository->createOrderItem($context->params);

        return $next($context);
    }
}
