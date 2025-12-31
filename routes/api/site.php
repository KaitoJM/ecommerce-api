<?php

use App\Http\Controllers\Site\BrandController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);
