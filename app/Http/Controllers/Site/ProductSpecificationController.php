<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\GetProductSpecificationRequest;
use App\Http\Resources\ProductSpecificationResource;
use App\Repositories\ProductSpecificationRepository;
use Illuminate\Http\Request;

class ProductSpecificationController extends Controller
{
    protected ProductSpecificationRepository $specificationRepository;

    public function __construct(ProductSpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductSpecificationRequest $request)
    {
        $filters = $request->only(['product_id', 'default', 'sale']);
        $products = $this->specificationRepository->getProductSpecifications($filters);

        return ProductSpecificationResource::collection($products);
    }
}
