<?php

namespace App\Http\Controllers;

use App\Http\Requests\productSpecification\CreateProductSpecificationRequest;
use App\Http\Requests\productSpecification\GetProductSpecificationRequest;
use App\Http\Requests\productSpecification\UpdateProductSpecificationRequest;
use App\Http\Resources\ProductSpecificationResource;
use App\Http\Services\ProductSpecificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductSpecificationController extends Controller
{
    protected ProductSpecificationService $specificationService;

    public function __construct(ProductSpecificationService $specificationService)
    {
        $this->specificationService = $specificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductSpecificationRequest $request)
    {
        $filters = $request->only(['product_id', 'default', 'sale']);
        $products = $this->specificationService->getProductSpecifications($filters);

        return ProductSpecificationResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductSpecificationRequest $request)
    {
        $product = $this->specificationService->createProductSpecification(
            $request->product_id,
            $request->combination,
            $request->price,
            $request->stock,
            $request->default ?? false,
            $request->sale ?? false,
            $request->sale_price ?? 0,
            $request->images
        );

        return response()->json($product)->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->specificationService->getProductSpecificationById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product specification not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductSpecificationRequest $request, string $id)
    {
        try {
            $product = $this->specificationService->updateProductSpecification(
                $id,
                $request->validated()
            );

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product specification not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->specificationService->deleteProductSpecification($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product specification not found'], 404);
        }

        return response()->json(null, 204);
    }

    public function destroyAllSpecificationByProductId(string $product_id)
    {
        try {
            $this->specificationService->deleteProductSpecifications( $product_id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Error while deleting product specifications'], 500);
        }

        return response()->json(null, 204);
    }
}
