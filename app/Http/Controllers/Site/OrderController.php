<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\CreateOrderRequest;
use App\Services\Site\Cart\CartService;
use App\Services\Site\Order\OrderService;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService,
    ) {}

    public function store(CreateOrderRequest $request) {
        $cart = $this->cartService->getCart($request->cart_id);

        $order = $this->orderService->createFromCart(
            $cart,
            $request->only(['cart_id', 'email', 'discount_total', 'tax_total'])
        );

        // update cart status in a separate process

        return response()->json($order)->setStatusCode(201);
    }
}
