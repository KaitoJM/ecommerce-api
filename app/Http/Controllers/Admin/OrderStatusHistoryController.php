<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusHistory\CreateOrderStatusHistoryRequest;
use App\Http\Requests\Admin\OrderStatusHistory\GetOrderStatusHistoryRequest;
use App\Http\Requests\Admin\OrderStatusHistory\UpdateOrderStatusHistoryRequest;
use App\Http\Resources\OrderStatusHistoryResource;
use App\Repositories\OrderStatusHistoryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderStatusHistoryController extends Controller
{
    protected OrderStatusHistoryRepository $orderStatusHistoryRepository;

    public function __construct(OrderStatusHistoryRepository $orderStatusHistoryRepository)
    {
        $this->orderStatusHistoryRepository = $orderStatusHistoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetOrderStatusHistoryRequest $request)
    {
        $history = $this->orderStatusHistoryRepository->getOrderStatusHistories($request->query('order_id'));

        return OrderStatusHistoryResource::collection($history);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderStatusHistoryRequest $request)
    {
        $history = $this->orderStatusHistoryRepository->createOrderStatusHistory(
            $request->order_id,
            $request->status_id,
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
            $history = $this->orderStatusHistoryRepository->getOrderStatusHistoryById($id);
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
            $history = $this->orderStatusHistoryRepository->updateOrderStatusHistory(
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
            $this->orderStatusHistoryRepository->deleteOrderStatusHistory($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order history not found'], 404);
        }

        return response()->json(null, 204);
    }
}
