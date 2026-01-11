<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\AddToCartRequest;
use App\Http\Requests\Site\GetCartItemsRequest;
use App\Http\Requests\Site\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Services\Site\CartItem\CartItemService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        try {
            $cartItem = $this->cartItemService->updateCartItem($id, $request->validated());
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        return response()->json($cartItem);
    }

    public function destroy(string $id) {
        try {
            $this->cartItemService->deleteCartItem($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        return response()->json(null, 204);
    }
}
