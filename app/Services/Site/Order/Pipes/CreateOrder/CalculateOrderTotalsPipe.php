<?php
namespace App\Services\Site\Order\Pipes\CreateOrder;

use Closure;
use App\Services\Site\Order\Contexts\AddOrderContext;

class CalculateOrderTotalsPipe
{
    public function handle(AddOrderContext $context, Closure $next)
    {
        $context->subtotal = $context->itemsModelled->sum('total');

        $context->discount_total = $context->params['discount_total'] ?? 0;
        $context->tax_total = $context->params['tax_total'] ?? 0;

        $context->total = $context->subtotal - $context->discount_total + $context->tax_total;

        return $next($context);
    }
}
