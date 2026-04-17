<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ProductController2;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/shirts', function () {
    return view('frontend.pages.shirts');
})->name('shirts.page');
Route::get('/pant', function () {
    return view('frontend.pages.pant');
})->name('pant.page');

// Route::get('shirts', [ProductController::class, 'tshirtPage'])->name('tshirts.page');
Route::get('/tshirts', [ProductController2::class, 'index'])->name('tshirts.index');
Route::get('/men-panjabi', [ProductController2::class, 'panjabi'])->name('panjabi.index');
Route::get('/pakistani-dress', [ProductController2::class, 'pakistaniDress'])->name('pakistani.dress');

//Cart Controller

// কার্ট পেজ
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// লগইন ইউজারের অ্যাড টু কার্ট (AJAX এর জন্য)
Route::post('/cart/add-db', [CartController::class, 'addToCart']);

// সিঙ্ক করার জন্য
Route::post('/cart/sync', [CartController::class, 'syncCart']);

require __DIR__.'/auth.php';
