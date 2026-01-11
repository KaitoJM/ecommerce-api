<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\CreateOrderRequest;
use App\Services\Site\Cart\CartService;
use App\Services\Site\Cart\OrderService;
use App\Services\Site\CartItem\CartItemService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService,
    ) {}

    public function store(CreateOrderRequest $request) {
        $cart = $this->cartService->getCart($request->cart_id);

        $order = $this->orderService->createFromCart($cart, $request->validated());
        // store cart items one by one
    }
}
