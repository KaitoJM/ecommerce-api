<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusHistory\CreateOrderStatusHistoryRequest;
use App\Http\Requests\Admin\OrderStatusHistory\GetOrderStatusHistoryRequest;
use App\Http\Requests\Admin\OrderStatusHistory\UpdateOrderStatusHistoryRequest;
use App\Http\Resources\OrderStatusHistoryResource;
use App\Services\OrderStatusHistoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderStatusHistoryController extends Controller
{
    protected OrderStatusHistoryService $orderStatusHistoryService;

    public function __construct(OrderStatusHistoryService $orderStatusHistoryService)
    {
        $this->orderStatusHistoryService = $orderStatusHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetOrderStatusHistoryRequest $request)
    {
        $history = $this->orderStatusHistoryService->getOrderStatusHistories($request->query('order_id'));

        return OrderStatusHistoryResource::collection($history);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderStatusHistoryRequest $request)
    {
        $history = $this->orderStatusHistoryService->createOrderStatusHistory(
            $request->only('order_id'),
            $request->only('status_id'),
            Auth::user()->id,
        );

        return response()->json(['data' => $history])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $history = $this->orderStatusHistoryService->getOrderStatusHistoryById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order history not found'], 404);
        }

        return response()->json($history);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusHistoryRequest $request, string $id)
    {
        try {
            $history = $this->orderStatusHistoryService->updateOrderStatusHistory(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order history not found'], 404);
        }

        return response()->json($history);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->orderStatusHistoryService->deleteOrderStatusHistory($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order history not found'], 404);
        }

        return response()->json(null, 204);
    }
}
