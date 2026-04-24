<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\OrderControllerAdmin;
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

    // Auth & User Management
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);
    Route::get('/roles', [RoleController::class, 'index']);

    // Inventory & Catalog
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);
    // Trash Product
    Route::get('products/trash', [ProductController::class, 'trashList']);
    // Restore Product
    Route::post('products/{id}/restore', [ProductController::class, 'restore']);
    //Parmanent delete (Hard Delete)
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('sizes', SizeController::class);

    Route::get('product-statuses', [ProductStatusController::class, 'index']);

    // Product Variant Routes
    Route::prefix('variants')->group(function () {
        Route::get('/product/{id}', [ProductVariantController::class, 'getProductVariants']);
        Route::post('/bulk-store', [ProductVariantController::class, 'storeBulkVariants']);
        Route::patch('/{id}/update-stock', [ProductVariantController::class, 'updateStock']);
        Route::delete('/{id}', [ProductVariantController::class, 'destroy']);
    });

    /*
|--------------------------------------------------------------------------
| Admin Order Management Routes
|--------------------------------------------------------------------------
*/

    // protected admin route
    Route::prefix('admin')->group(function () {

        // To collect all orders

        //Each Order Details
        Route::get('/orders', [OrderControllerAdmin::class, 'index']);
        Route::get('/orders/{id}', [OrderControllerAdmin::class, 'show']);

        // order update
        Route::put('/orders/{id}/status', [OrderControllerAdmin::class, 'updateStatus']);
    });
});
