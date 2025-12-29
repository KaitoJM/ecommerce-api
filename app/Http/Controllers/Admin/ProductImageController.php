<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\productImage\CreateProductImageRequest;
use App\Http\Requests\Admin\productImage\GetProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Repositories\ProductImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

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
        $path = $request->file('image')->store('product-images', 's3');
        // $source = url('/') . '/storage/' . $path;
        $source = Storage::disk('s3')->url($path);

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
            $image = $this->imageService->getImageById($id);
            $imageSource = $image->source;

            $this->imageService->deleteImage($id);

            // This is for local
            // // Remove base URL and /storage/
            // $path = str_replace(
            //     url('/') . '/storage/',
            //     '',
            //     $imageSource
            // );

            // // Delete file from storage/app/public
            // if (Storage::disk('public')->exists($path)) {
            //     Storage::disk('public')->delete($path);
            // }

            // This is for s3
            // Extract path relative to S3 bucket
            $path = parse_url($imageSource, PHP_URL_PATH);
            // Remove leading slash
            $path = ltrim($path, '/');

            // Delete file from S3
            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->json(null, 204);
    }


    public function updateCoverImage(string $id) {
        try {
            $this->imageService->setCoverImage($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->json(null, 200);
    }
}
