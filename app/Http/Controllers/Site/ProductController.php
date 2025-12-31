<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\GetProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function index(GetProductRequest $request) {
        $filters = [
            'published' => true,
            'categories' => $request->has('categories') ? explode(',', $request->query('categories')) : null,
            'brands' => $request->has('brands') ? explode(',', $request->query('brands')) : null,
            ...$request->only(['brand', 'price_min', 'price_max'])
        ];

        $pagination = $request->only(['page', 'per_page']);

        $products = $this->productRepository->getProducts($request->query('search'), $filters, $pagination);

        return ProductResource::collection($products);
    }

    public function show(string $id)
    {
        try {
            $product = $this->productRepository->getProductById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['data' => new ProductResource($product)]);
    }
}
