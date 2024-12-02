<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
//use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Routes للمتاجر
Route::get('/stores', [StoreController::class, 'index']);// عرض جميع المتاجر
Route::post('/stores', [StoreController::class, 'store']); // إضافة متجر جديد
Route::get('/stores/{id}', [StoreController::class, 'show']); // عرض متجر معين

// Routes للمنتجات
Route::get('/products', [ProductController::class, 'index']); // عرض جميع المنتجات
Route::post('/stores/{storeId}/products', [ProductController::class, 'store']); // إضافة منتج إلى متجر معين
Route::get('/products/{id}', [ProductController::class, 'show']); // عرض منتج معين

//Routes للسلة 
Route::get('/cart', [CartController::class, 'index']);//عرض محتويات السلة
Route::post('/cart', [CartController::class, 'addToCart']);//إضافة منتج إلى السلة
Route::delete('/cart/{id}', [CartController::class, 'removeFromCart']);// حذف منتج من السلة
Route::post('/cart/confirm', [CartController::class, 'confirmOrder']);// تأكيد الطلب