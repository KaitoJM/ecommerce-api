<?php

namespace App\Services\Site\Order\Pipes\CreateOrder;

use Closure;
use App\Services\Site\Order\Contexts\AddOrderContext;

class PrepareOrderParametersPipe
{
    public function handle(AddOrderContext $context, Closure $next)
    {
        $context->params = [
            ...$context->params,
            'customer_id' => $context->customerId ?? '',
            'is_guest' => !$context->customerId,
            'subtotal' => $context->subtotal,
            'discount_total' => $context->discount_total,
            'tax_total' => $context->tax_total,
            'total' => $context->total,
            'status_id' => $context->status_id,
        ];

        return $next($context);
    }
}
