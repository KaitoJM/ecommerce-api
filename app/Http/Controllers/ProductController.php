<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\CreateProductRequest;
use App\Http\Requests\product\GetProductsRequest;
use App\Http\Requests\product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;
use App\Http\Services\ProductSpecificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;
    protected ProductSpecificationService $productSpecificationService;

    public function __construct(ProductService $productService, 
    ProductSpecificationService $productSpecificationService)
    {
        $this->productService = $productService;
        $this->productSpecificationService = $productSpecificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductsRequest $request)
    {
        $filters = $request->only(['published']);
        $pagination = $request->only(['page', 'per_page']);

        $products = $this->productService->getProducts($request->query('search'), $filters, $pagination);

        return ProductResource::collection($products);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->createProduct($request->only(['name', 'summary']));

        // attach default specification.
        $this->productSpecificationService->createProductSpecification(
            $product->id, 
            '', 
            $request->price ?? 0, 
            $request->stock ?? 0, 
            true
        );

        // attach categories when provided
        $categories = $request->input('categories', []);
        if (!empty($categories)) {
            $this->productService->attachCategories($product, $categories);
        }
        
        return response()->json(['data' => $product])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->productService->getProductById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = $this->productService->updateProduct(
                $id, 
                $request->validated()
            );

            // attach categories when provided
            $categories = $request->input('categories', []);
            if (!empty($categories)) {
                $this->productService->attachCategories($product, $categories);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['data' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        try {
            $this->productService->deleteProduct($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(null, 204);
    }
}
