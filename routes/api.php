<?php

use Illuminate\Support\Facades\Route;

// Site (customer-facing)
Route::prefix('site')->group(base_path('routes/api/site.php'));

// Admin
Route::group([], base_path('routes/api/admin.php'));
