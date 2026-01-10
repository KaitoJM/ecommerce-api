<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Site\BrandController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CartItemController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ProductImageController;
use App\Http\Controllers\Site\ProductSpecificationController;
use App\Http\Controllers\Site\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegistrationController::class, 'index']);
Route::post('/login', [AuthController::class, 'loginCustomer']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/product-images', [ProductImageController::class, 'index']);
Route::get('/product-specifications', [ProductSpecificationController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);
Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('/carts', CartController::class);
    Route::apiResource('/cart-item', CartItemController::class);
});
