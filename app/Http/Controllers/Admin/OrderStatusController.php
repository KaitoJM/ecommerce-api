<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatus\CreateOrderStatusRequest;
use App\Http\Requests\Admin\OrderStatus\GetOrderStatusRequest;
use App\Http\Requests\Admin\OrderStatus\UpdateOrderStatusRequest;
use App\Http\Resources\OrderStatusResource;
use App\Repositories\OrderStatusService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    protected OrderStatusService $orderStatusService;

    public function __construct(OrderStatusService $orderStatusService) {
        $this->orderStatusService = $orderStatusService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetOrderStatusRequest $request)
    {
        $orderStatuses = $this->orderStatusService->getOrderStatuses($request->query('search'));

        return OrderStatusResource::collection($orderStatuses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderStatusRequest $request)
    {
        $orderStatus = $this->orderStatusService->createOrderStatus(
            $request->only([
                'status',
                'color_code',
                'description',
            ])
        );

        return response()->json(['data' => $orderStatus])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $orderStatus = $this->orderStatusService->getOrderStatusById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order status not found'], 404);
        }

        return response()->json(['data' => new OrderStatusResource($orderStatus)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusRequest $request, string $id)
    {
        try {
            $orderStatus = $this->orderStatusService->updateOrderStatus(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order status not found'], 404);
        }

        return response()->json($orderStatus);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->orderStatusService->deleteOrderStatus($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order status not found'], 404);
        }

        return response()->json(null, 204);
    }
}
