<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\CreateProductRequest;
use App\Http\Requests\product\GetProductsRequest;
use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductsRequest $request)
    {
        $products = $this->productService->getProducts($request->query('search'));

        return ProductResource::collection($products);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = $this->productService->createProduct($request->only(['name', 'description', 'price']));

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->getProductById($id);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = $this->productService->updateProduct(
            $id, 
            $request->only(['name', 'description', 'price'])
        );

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->productService->deleteProduct($id);

        return response()->json(null, 204);
    }
}
