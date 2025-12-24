<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\CreateOrderRequest;
use App\Http\Requests\Admin\OrderItem\GetOrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Services\OrderItemService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    protected OrderItemService $orderItemService;

    public function __construct(OrderItemService $orderItemService) {
        $this->orderItemService = $orderItemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetOrderItemRequest $request)
    {
        $orderItems = $this->orderItemService->getOrderItems($request->query('search'));

        return OrderItemResource::collection($orderItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        $orderItem = $this->orderItemService->createOrderItem(
            $request->only([
                'order_id',
                'product_id',
                'product_specification_id',
                'product_snapshot_name',
                'product_snapshot_price',
                'quantity',
                'total',
            ])
        );

        return response()->json(['data' => $orderItem])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $orderItem = $this->orderItemService->getOrderItemById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        return response()->json(['data' => new OrderItemResource($orderItem)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $orderItem = $this->orderItemService->updateOrderItem(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        return response()->json($orderItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->orderItemService->deleteOrderItem($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        return response()->json(null, 204);
    }
}
