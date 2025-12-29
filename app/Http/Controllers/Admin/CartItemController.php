<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\cartItem\CreateCartItemRequest;
use App\Http\Requests\Admin\cartItem\GetCartItemRequest;
use App\Http\Requests\Admin\cartItem\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Repositories\CartItemRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected CartItemRepository $cartItemRepository;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetCartItemRequest $request)
    {
        $cartItems = $this->cartItemRepository->getCartItems($request->query('search'));

        return CartItemResource::collection($cartItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCartItemRequest $request)
    {
        $cartItem = $this->cartItemRepository->createCartItem(
            $request->only([
                'cart_id',
                'product_id',
                'product_specification_id',
                'quantity',
            ])
        );

        return response()->json(['data' => $cartItem])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cartItem = $this->cartItemRepository->getCartItemById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        return response()->json(['data' => new CartItemResource($cartItem)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, string $id)
    {
        try {
            $cartItem = $this->cartItemRepository->updateCartItem(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        return response()->json($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->cartItemRepository->deleteCartItem($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        return response()->json(null, 204);
    }
}
