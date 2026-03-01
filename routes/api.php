<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Models\Role;
use Illuminate\Foundation\Auth\User;

//=====this route is test route when command php artisan install:api====

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ২. প্রোটেক্টেড রাউট (যেগুলো টোকেন ছাড়া কাজ করবে না)
Route::middleware('auth:sanctum')->group(function () {

    // লগইন করা ইউজারের ডাটা দেখার জন্য (লারাভেলের ডিফল্টটা এখানে রাখুন)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // ইউজার লিস্ট দেখার জন্য
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    //role api
    Route::get('/roles', [RoleController::class, 'index']);

    // লগআউট রাউট
    Route::post('/logout', [AuthController::class, 'logout']);
});
