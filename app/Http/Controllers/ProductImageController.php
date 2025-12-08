<?php

namespace App\Http\Controllers;

use App\Http\Requests\productImage\CreateProductImageRequest;
use App\Http\Requests\productImage\GetProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Http\Services\ProductImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    protected ProductImageService $imageService;

    public function __construct(ProductImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetProductImageRequest $request)
    {   
        $filters = $request->only(['cover']);
        $images = $this->imageService->getImages($request->product_id, $filters);

        return ProductImageResource::collection($images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductImageRequest $request)
    {
        // process upload file
        $source = $request->file('image')->store('product-images', 'public');

        // save file information
        $image = $this->imageService->createProductImage($source, $request->product_id, $request->cover);

        return response()->json($image)->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $image = $this->imageService->getImageById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->json($image);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->imageService->deleteImage($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->json(null, 204);
    }
}
