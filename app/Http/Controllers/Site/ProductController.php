<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\GetProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
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
            ...$request->only(['categories', 'brand', 'price_min', 'price_max'])
        ];
        $pagination = $request->only(['page', 'per_page']);

        $products = $this->productRepository->getProducts($request->query('search'), $filters, $pagination);

        return ProductResource::collection($products);
    }
}
