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

// to open cart page
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Login User add to cart for AJAX 
Route::post('/cart/add-db', [CartController::class, 'addToCart']);

Route::post('/cart/sync', [CartController::class, 'syncCart']);

//delelte cart item
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);

//product details page

Route::get('/product/{id}', [ProductController2::class, 'productDetails'])->name('product.details');

//cart update route for login user
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity']);

require __DIR__ . '/auth.php';
