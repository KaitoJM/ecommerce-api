<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\AddToCartRequest;
use App\Http\Resources\CartItemResource;
use App\Services\Site\CartItem\CartItemService;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function __construct(
        protected CartItemService $cartItemService
    ) {}

    /**
     * Add Item to Cart
     */
    public function addToCart(AddToCartRequest $request)
    {
        $cartItem = $this->cartItemService->addToCart(
            $request->cart_id,
            $request->product_id,
            $request->product_specification_id,
            $request->qty
        );

        return CartItemResource::collection($cartItem);
    }
}
