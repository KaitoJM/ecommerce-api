<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\GetBrandRequest;
use App\Http\Resources\BrandResource;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository) {
        $this->brandRepository = $brandRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetBrandRequest $request)
    {
        $brands = $this->brandRepository->getBrands($request->query('search'));

        return BrandResource::collection($brands);
    }
}
