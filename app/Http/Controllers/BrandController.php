<?php

namespace App\Http\Controllers;

use App\Http\Requests\brand\CreateBrandRequest;
use App\Http\Requests\brand\GetBrandRequest;
use App\Http\Requests\brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Http\Services\BrandService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetBrandRequest $request)
    {
        $brands = $this->brandService->getBrands($request->query('search'));

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBrandRequest $request)
    {
        $brand = $this->brandService->createBrand($request->only(['name']));

        return response()->json(['data' => $brand])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $brand = $this->brandService->getBrandById($id);
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
            $brand = $this->brandService->updateBrand(
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
            $this->brandService->deleteBrand($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        return response()->json(null, 204);
    }
}
