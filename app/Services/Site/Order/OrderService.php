<?php

namespace App\Services\Site\Order;

use App\Models\Cart;
use App\Repositories\OrderRepository;
use App\Services\Site\CartItem\CartItemService;
use App\Services\Site\Order\Contexts\AddOrderContext;
use App\Services\Site\Order\Validation\NoTheSameCartIdRule;
use App\Services\Site\OrderItem\OrderItemService;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class OrderService {
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CartItemService $cartItemService,
        protected OrderItemService $orderItemService,
        private Pipeline $pipeline
    ) {}

    public function createFromCart(Cart $cart, $params) {
        $items = $this->cartItemService->getCartItems($cart->id);
        $subtotal = $items->sum('specification.price');
        $discount = $params['discount_total'];
        $tax = $params['tax_total'];
        $total = $subtotal - $discount + $tax;

        $params = [
            ...$params,
            'customer_id' => $cart['customer_id'],
            'session_id' => $cart['session_id'],
            'subtotal' => $subtotal,
            'total' => $total
        ];

        $context = new AddOrderContext($cart->id);

        $this->pipeline
            ->send($context)
            ->through([
                NoTheSameCartIdRule::class
            ])
            ->thenReturn();

        // process transaction
        return DB::transaction(function() use ($params, $items) {
            $order = $this->orderRepository->createOrder($params);

            // create order items
            foreach ($items as $cartItem) {
               $this->orderItemService->createFromCartItem($order, $cartItem);
            }

            return $order;
        });

    }
}
