<?php

namespace App\Services\Site\Order;

use App\Models\Cart;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
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
        protected OrderStatusRepository $statusRepository,
        private Pipeline $pipeline
    ) {}

    public function createFromCart(Cart $cart, $params) {
        $items = $this->cartItemService->getCartItems($cart->id);
        $subtotal = $items->sum(function($item){
            return $item->specification->price * $item->quantity;
        });

        $discount = $params['discount_total'] ?? 0;
        $tax = $params['tax_total'] ?? 0;
        $total = $subtotal - $discount + $tax;
        $statuses = $this->statusRepository->getOrderStatuses('Pending');

        $params = [
            ...$params,
            'customer_id' => $cart['customer_id'],
            'session_id' => $cart['session_id'],
            'subtotal' => $subtotal,
            'total' => $total,
            'status_id' => $statuses[0]->id
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
