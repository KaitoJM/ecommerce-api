<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\cart\CreateCartRequest;
use App\Http\Requests\Admin\cart\GetCartRequest;
use App\Http\Requests\Admin\cart\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetCartRequest $request)
    {
        $carts = $this->cartService->getCarts($request->query('search'));

        return CartResource::collection($carts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCartRequest $request)
    {
        $cart = $this->cartService->createCart(
            $request->only([
                'user_id',
                'session_id',
                'status',
                'expires_at',
            ])
        );

        return response()->json(['data' => $cart])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cart = $this->cartService->getCartById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        return response()->json(['data' => new CartResource($cart)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, string $id)
    {
        try {
            $cart = $this->cartService->updateCart(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        return response()->json($cart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->cartService->deleteCart($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        return response()->json(null, 204);
    }
}
