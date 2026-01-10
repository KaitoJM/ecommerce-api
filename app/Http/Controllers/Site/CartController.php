<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\AddToCartRequest;
use App\Http\Resources\CartItemResource;
use App\Repositories\CustomerRepository;
use App\Services\Site\CartItem\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CustomerRepository $customerRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $user->id]);

        if (!$customer) {
            throw new NotFoundHttpException('Customer not found.');
        }

        $cartItem = $this->cartService->getActiveCart($customer->id);

        return CartItemResource::collection($cartItem);
    }

    public function store(Request $request) {
        $user = Auth::user();
        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $user->id]);

        if (!$customer) {
            throw new NotFoundHttpException('Customer not found.');
        }

        $cart = $this->cartService->addCart($customer->id, $request->status);

        return response()->json($cart)->setStatusCode(201);
    }
}
