<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\product\CreateProductRequest;
use App\Http\Requests\Admin\product\GetProductsRequest;
use App\Http\Requests\Admin\product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSpecificationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;
    protected ProductSpecificationRepository $productSpecificationRepository;

    public function __construct(ProductRepository $productRepository,
    ProductSpecificationRepository $productSpecificationRepository)
    {
        $this->productRepository = $productRepository;
        $this->productSpecificationRepository = $productSpecificationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductsRequest $request)
    {
        $filters = $request->only(['published']);
        $pagination = $request->only(['page', 'per_page']);

        $products = $this->productRepository->getProducts($request->query('search'), $filters, $pagination);

        return ProductResource::collection($products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $product = $this->productRepository->createProduct($request->only(['name', 'summary']));

        // attach default specification.
        $this->productSpecificationRepository->createProductSpecification(
            $product->id,
            '',
            $request->price ?? 0,
            $request->stock ?? 0,
            true
        );

        // attach categories when provided
        $categories = $request->input('categories', []);
        if (!empty($categories)) {
            $this->productRepository->attachCategories($product, $categories);
        }

        return response()->json(['data' => $product])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->productRepository->getProductById($id);
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
            $product = $this->productRepository->updateProduct(
                $id,
                $request->validated()
            );

            // attach categories when provided
            $categories = $request->input('categories', []);
            if (!empty($categories)) {
                $this->productRepository->attachCategories($product, $categories);
            }

            // update default specification
            $defaultSpecification = $this->productSpecificationRepository->getProductDefaultSpecification($id);
            if ($defaultSpecification && ($request->has('price') || $request->has('stock'))) {
                $this->productSpecificationRepository->updateProductSpecification(
                    $defaultSpecification->id,
                    [
                        'price' => $request->price ?? 0,
                        'stock' => $request->stock ?? 0,
                    ]
                );
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
            $this->productRepository->deleteProduct($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(null, 204);
    }
}
