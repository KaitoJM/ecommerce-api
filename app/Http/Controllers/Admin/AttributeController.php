<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\attribute\CreateAttributeRequest;
use App\Http\Requests\Admin\attribute\GetAttributeRequest;
use App\Http\Requests\Admin\attribute\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Services\AttributeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected AttributeService $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetAttributeRequest $request)
    {
        $attributes = $this->attributeService->getAttributes($request->query('search'));

        return AttributeResource::collection($attributes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAttributeRequest $request)
    {
        $attribute = $this->attributeService->createAttribute($request->only(['attribute', 'selection_type']));

        return response()->json(['data' => $attribute])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $attribute = $this->attributeService->getAttributeById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Attribute not found'], 404);
        }

        return response()->json(['data' => new AttributeResource($attribute)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, string $id)
    {
        try {
            $attribute = $this->attributeService->updateAttribute(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Attribute not found'], 404);
        }

        return response()->json($attribute);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->attributeService->deleteAttribute($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Attribute not found'], 404);
        }

        return response()->json(null, 204);
    }
}
