<?php
namespace App\Services\Site\OrderItem\Pipes\CreateOrderItem;

use Closure;
use App\Services\Site\Order\Contexts\AddOrderContext;
use App\Services\Site\OrderItem\Contexts\AddOrderItemContext;

class PrepareOrderItemParamsPipe
{
    public function handle(AddOrderItemContext $context, Closure $next)
    {
        $total = $context->specification->price * $context->quantity;

        $context->params = [
            'order_id' => $context->order->id,
            'product_id' => $context->product->id,
            'product_specification_id' => $context->specification->id,
            'product_snapshot_name' => $context->product->name,
            'product_snapshot_price' => $context->specification->price,
            'quantity' => $context->quantity,
            'total' => $total,
        ];

        return $next($context);
    }
}
