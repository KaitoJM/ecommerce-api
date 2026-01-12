<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\CreateOrderRequest;
use App\Repositories\CustomerRepository;
use App\Services\Site\Cart\CartService;
use App\Services\Site\Order\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CustomerRepository $customerRepository,
    ) {}

    public function store(CreateOrderRequest $request) {
        $user = Auth::user();
        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $user->id]);

        $order = $this->orderService->createOrder(
            $request->only(['email', 'discount_total', 'tax_total']),
            $request->items,
            $customer->id
        );

        return response()->json($order->order)->setStatusCode(201);
    }

    public function storeAsGuest(CreateOrderRequest $request) {
        $order = $this->orderService->createOrder(
            $request->only(['email', 'discount_total', 'tax_total']),
            $request->items
        );

        return response()->json($order->order)->setStatusCode(201);
    }
}
