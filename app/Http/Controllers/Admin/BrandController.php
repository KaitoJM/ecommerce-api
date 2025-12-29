<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\brand\CreateBrandRequest;
use App\Http\Requests\Admin\brand\GetBrandRequest;
use App\Http\Requests\Admin\brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Repositories\BrandRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBrandRequest $request)
    {
        $brand = $this->brandRepository->createBrand($request->only(['name']));

        return response()->json(['data' => $brand])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $brand = $this->brandRepository->getBrandById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $id)
    {
        try {
            $brand = $this->brandRepository->updateBrand(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        return response()->json($brand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->brandRepository->deleteBrand($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        return response()->json(null, 204);
    }
}
