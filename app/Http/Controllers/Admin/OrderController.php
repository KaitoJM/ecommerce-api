<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\CreateOrderRequest;
use App\Http\Requests\Admin\Order\GetOrderRequest;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetOrderRequest $request)
    {
        $orders = $this->orderRepository->getOrders($request->query('search'));

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        $order = $this->orderRepository->createOrder(
            $request->only([
                'customer_id',
                'session_id',
                'cart_id',
                'status_id',
                'subtotal',
                'discount_total',
                'tax_total',
                'total',
            ])
        );

        return response()->json(['data' => $order])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = $this->orderRepository->getOrderById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(['data' => new OrderResource($order)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        try {
            $order = $this->orderRepository->updateOrder(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->orderRepository->deleteOrder($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(null, 204);
    }
}
