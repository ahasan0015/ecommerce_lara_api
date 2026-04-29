<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\OrderControllerAdmin;
use App\Http\Controllers\Api\OrderStatusController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductStatusController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductVariantController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // 1. Auth & User Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 2. User & Role Management
    Route::apiResource('users', UserController::class);
    Route::get('/roles', [RoleController::class, 'index']);

    // 3. Catalog Management (Categories, Brands, Colors, Sizes)
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('sizes', SizeController::class);
    Route::get('product-statuses', [ProductStatusController::class, 'index']);

    // 4. Product Management (Including Trash & Restore)
    Route::prefix('products')->group(function () {
        Route::get('/trash', [ProductController::class, 'trashList']);
        Route::post('/{id}/restore', [ProductController::class, 'restore']);
        Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete']);
    });
    Route::apiResource('products', ProductController::class);

    // 5. Product Variant Management
    Route::prefix('variants')->group(function () {
        Route::get('/product/{id}', [ProductVariantController::class, 'getProductVariants']);
        Route::post('/bulk-store', [ProductVariantController::class, 'storeBulkVariants']);
        Route::patch('/{id}/update-stock', [ProductVariantController::class, 'updateStock']);
        Route::delete('/{id}', [ProductVariantController::class, 'destroy']);
    });

    // 6. Admin Order Management
    Route::prefix('admin')->group(function () {
        Route::get('/orders', [OrderControllerAdmin::class, 'index']);
        Route::get('/orders/{id}', [OrderControllerAdmin::class, 'show']);
        Route::put('/orders/{id}/status', [OrderControllerAdmin::class, 'updateStatus']);

        // (api/admin/order-statuses)
        Route::get('/order-statuses', [OrderStatusController::class, 'index']);
    });
});
