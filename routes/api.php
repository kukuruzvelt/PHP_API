<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', [UserController::class, 'get']);
Route::middleware(['auth:sanctum'])->post('/pay', [UserController::class, 'pay']);

Route::middleware(['auth:sanctum'])->get('/cart', [CartController::class, 'get']);
Route::middleware(['auth:sanctum'])->post('/cart/add', [CartController::class, 'add']);
Route::middleware(['auth:sanctum'])->post('/cart/remove', [CartController::class, 'remove']);

Route::middleware(['auth:sanctum'])->post('/order/create', [OrderController::class, 'create']);
Route::middleware(['auth:sanctum'])->get('/order/get', [OrderController::class, 'get']);
Route::middleware(['auth:sanctum'])->get('/order/all', [OrderController::class, 'getAll']);

Route::get('/catalog', [CatalogController::class, 'get']);

Route::get('/product', [ProductController::class, 'get']);

Route::get('/category/all', [CategoryController::class, 'getAll']);
Route::get('/category', [CategoryController::class, 'get']);


