<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\GetProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Repositories\ProductImageRepository;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function __construct(protected ProductImageRepository $imageRepository){}

    public function index(GetProductImageRequest $request)
    {
        $filters = $request->only(['cover']);
        $images = $this->imageRepository->getImages($request->product_id, $filters);

        return ProductImageResource::collection($images);
    }
}
