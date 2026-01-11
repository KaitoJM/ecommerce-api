<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use App\Services\Site\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CustomerRepository $customerRepository
    ) {}

    public function store(Request $request) {
        $user = Auth::user();
        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $user->id]);

        if (!$customer) {
            throw new NotFoundHttpException('Customer not found.');
        }

        $cart = $this->cartService->addCart($customer->id, $request->status);

        return response()->json($cart)->setStatusCode(201);
    }

    public function active()
    {
        $user = Auth::user();
        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $user->id]);

        if (!$customer) {
            throw new NotFoundHttpException('Customer not found.');
        }

        $cartItem = $this->cartService->getOrCreateActiveCart($customer->id);

        return $cartItem;
    }

    public function storeAsGuest(Request $request) {
        $cart = $this->cartService->addCartAsGuest($request->status);

        return response()->json($cart)->setStatusCode(201);
    }
}
