<?php

namespace App\Services\Site\Order;

use App\Models\Cart;
use App\Repositories\OrderRepository;
use App\Services\Site\CartItem\CartItemService;
use App\Services\Site\OrderItem\OrderItemService;
use Illuminate\Support\Facades\DB;

class OrderService {
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CartItemService $cartItemService,
        protected OrderItemService $orderItemService
    ) {}

    public function createFromCart(Cart $cart, $params) {
        $items = $this->cartItemService->getCartItems($cart->id);

        $params = [
            ...$params,
            'customer_id' => $cart->customer_id,
            'session_id' => $cart->session_id
        ];

        // validate if cart Id already exist in Orders

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
