<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\productAttribute\CreateProductAttributeRequest;
use App\Http\Requests\Admin\productAttribute\GetProductAttributeRequest;
use App\Http\Requests\Admin\productAttribute\UpdateProductAttributeRequest;
use App\Http\Resources\ProductAttributeResource;
use App\Http\Services\ProductAttributeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    protected ProductAttributeService $prodAttrService;

    public function __construct(ProductAttributeService $prodAttrService)
    {
        $this->prodAttrService = $prodAttrService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductAttributeRequest $request)
    {
        $productAttributes = $this->prodAttrService->getProductAttributes($request->product_id);

        return ProductAttributeResource::collection($productAttributes);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductAttributeRequest $request)
    {
        $productAttribute = $this->prodAttrService->createProductAttribute(
            $request->product_id,
            $request->attribute_id,
            $request->value,
            $request->color_value ?? null
        );

        return response()->json(['data' => $productAttribute])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $productAttribute = $this->prodAttrService->getProductAttributeById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product attribute not found'], 404);
        }

        return response()->json($productAttribute);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductAttributeRequest $request, string $id)
    {
        try {
            $attribute = $this->prodAttrService->updateProductAttribute(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product attribute not found'], 404);
        }

        return response()->json($attribute);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->prodAttrService->deleteProductAttribute($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product attribute not found'], 404);
        }

        return response()->json(null, 204);
    }
}
