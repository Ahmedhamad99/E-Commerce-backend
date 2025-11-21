<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

Route::post('auth/register', [AuthController::class,'register']);
Route::post('auth/login', [AuthController::class,'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('auth/logout',[AuthController::class,'logout']);
    Route::get('auth/me',[AuthController::class,'me']);
 
    Route::apiResource('products',ProductController::class);
    
    Route::get('cart',[CartController::class,'index']);
    Route::post('cart',[CartController::class,'add']);
    Route::delete('cart/{product}',[CartController::class,'remove']);

    Route::get('orders',[OrderController::class,'index']);
    Route::get('orders/{order}',[OrderController::class,'show']);
    Route::post('orders',[OrderController::class,'store']);
});
