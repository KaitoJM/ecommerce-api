<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductSpecificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/change-logs', [GithubController::class, 'changeLogs']);

// Public route - no authentication required
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/product-images', [ProductImageController::class, 'index']);
Route::get('/product-images/{id}', [ProductImageController::class, 'show']);

// Protected routes - authentication required
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::patch('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/product-images', [ProductImageController::class, 'store']);
    Route::delete('/product-images/{id}', [ProductImageController::class, 'destroy']);
    Route::patch('/product-images-cover/{id}', [ProductImageController::class, 'updateCoverImage']);
});
Route::apiResource('/brands', BrandController::class)->middleware('auth:sanctum');
Route::apiResource('/attributes', AttributeController::class)->middleware('auth:sanctum');
Route::apiResource('/product-attributes', ProductAttributeController::class)->middleware('auth:sanctum');
Route::apiResource('/product-specifications', ProductSpecificationController::class)->middleware('auth:sanctum');
Route::delete('/product-specifications-delete-by-product/{id}', [ProductSpecificationController::class, 'destroyAllSpecificationByProductId'])->middleware('auth:sanctum');
Route::apiResource('/users', UserController::class)->middleware('auth:sanctum');
Route::apiResource('/customers', CustomerController::class)->middleware('auth:sanctum');
Route::apiResource('/carts', CartController::class)->middleware('auth:sanctum');
Route::apiResource('/cart-items', CartItemController::class)->middleware('auth:sanctum');
