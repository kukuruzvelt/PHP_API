<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
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

Route::middleware(['auth:sanctum'])->get('/cart', [CartController::class, 'get']);
Route::middleware(['auth:sanctum'])->put('/cart/add', [CartController::class, 'add']);
Route::middleware(['auth:sanctum'])->delete('/cart/remove', [CartController::class, 'remove']);

Route::get('/catalog', [CatalogController::class, 'get']);

Route::get('/product', [ProductController::class, 'get']);

Route::get('/categories', [CategoryController::class, 'getAll']);
Route::get('/category', [CategoryController::class, 'get']);


