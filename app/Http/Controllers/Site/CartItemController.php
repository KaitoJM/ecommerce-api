<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\AddToCartRequest;
use App\Http\Requests\Site\GetCartItemsRequest;
use App\Http\Requests\Site\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Services\Site\CartItem\CartItemService;

class CartItemController extends Controller
{
    public function __construct(
        protected CartItemService $cartItemService
    ) {}

    public function index(GetCartItemsRequest $request) {
        $cartItems = $this->cartItemService->getCartItems($request->cart_id);

        return response()->json(CartItemResource::collection($cartItems));
    }

    /**
     * Add Item to Cart
     */
    public function store(AddToCartRequest $request)
    {
        $cartItem = $this->cartItemService->addToCart(
            $request->cart_id,
            $request->product_id,
            $request->product_specification_id,
            $request->qty
        );

        return $cartItem;
    }

    public function update(UpdateCartItemRequest $request, string $id) {
        return $this->cartItemService->updateCartItem($id, $request->only(['quantity']));
    }

    public function destroy(string $id) {
        return $this->cartItemService->deleteCartItem($id);
    }
}
