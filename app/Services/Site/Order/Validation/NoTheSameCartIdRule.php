<?php

namespace App\Services\Site\Order\Validation;

use App\Repositories\OrderRepository;
use App\Services\Site\Order\Contexts\AddOrderContext;
use Closure;
use InvalidArgumentException;

class NoTheSameCartIdRule
{
    public function __construct(
        private OrderRepository $repository
    ) {}

    public function handle(AddOrderContext $context, Closure $next)
    {
        $hasTheSameCartId = $this->repository
            ->getOrders(null, ['cart_id' => $context->cartId]);

        if ($hasTheSameCartId?->count()) {
            throw new InvalidArgumentException(
                'Cart ID already exist in order records.'
            );
        }

        return $next($context);
    }
}
