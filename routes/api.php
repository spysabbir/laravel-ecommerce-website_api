<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;



// Guest routes
Route::group(['middleware' => 'guest:sanctum'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Frontend routes
    Route::get('/', [FrontendController::class, 'index']);
    Route::get('product', [FrontendController::class, 'product']);
    Route::get('product/{id}', [FrontendController::class, 'productById']);

    Route::get('cart', [FrontendController::class, 'cart']);
    Route::post('cart/add', [FrontendController::class, 'cartAdd']);
    Route::post('cart/update', [FrontendController::class, 'cartUpdate']);
    Route::post('cart/remove', [FrontendController::class, 'cartRemove']);

    Route::get('checkout', [FrontendController::class, 'checkout']);

    Route::post('order', [FrontendController::class, 'order']);
    Route::get('order', [FrontendController::class, 'order']);
    Route::get('order/{id}', [FrontendController::class, 'orderById']);
    Route::get('order/{id}/cancel', [FrontendController::class, 'orderCancel']);
    Route::get('order/{id}/invoice', [FrontendController::class, 'orderInvoice']);

    Route::get('wishlist', [FrontendController::class, 'wishlist']);
    Route::post('wishlist/add', [FrontendController::class, 'wishlistAdd']);
    Route::post('wishlist/remove', [FrontendController::class, 'wishlistRemove']);

});

// Authenticated routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Both routes are accessible to authenticated users
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('profile', [AuthController::class, 'profile']);
    Route::put('profile/update', [AuthController::class, 'profileUpdate']);
    Route::put('password/update', [AuthController::class, 'passwordUpdate']);

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);

        Route::get('user', [AdminController::class, 'user']);
        Route::get('user/{id}', [AdminController::class, 'userById']);

        Route::resource('category', CategoryController::class);
        Route::resource('subcategory', SubcategoryController::class);
        Route::resource('brand', BrandController::class);
        Route::resource('product', ProductController::class);
        Route::resource('coupon', CouponController::class);
    });

    // Frontend routes

});


