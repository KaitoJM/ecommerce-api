<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\category\createCategoryRequest;
use App\Http\Requests\Admin\category\GetCategoryRequest;
use App\Http\Requests\Admin\category\updateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetCategoryRequest $request)
    {
        $categories = $this->categoryRepository->getCategories($request->query('search'));

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(createCategoryRequest $request)
    {
        $category = $this->categoryRepository->createCategory($request->only(['name', 'description']));

        return response()->json(['data' => $category])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = $this->categoryRepository->getCategoryById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['data' => new CategoryResource($category)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateCategoryRequest $request, string $id)
    {
        try {
            $category = $this->categoryRepository->updateCategory(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->categoryRepository->deleteCategory($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(null, 204);
    }
}
