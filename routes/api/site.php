<?php

use App\Http\Controllers\Site\BrandController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ProductImageController;
use App\Http\Controllers\Site\ProductSpecificationController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/product-images', [ProductImageController::class, 'index']);
Route::get('/product-specifications', [ProductSpecificationController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);
