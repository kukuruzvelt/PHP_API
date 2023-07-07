<?php

use App\Http\Controllers\CartController;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->get('/cart', [CartController::class, 'get']);
Route::middleware(['auth:sanctum'])->put('/cart/add', [CartController::class, 'add']);
Route::middleware(['auth:sanctum'])->delete('/cart/remove', [CartController::class, 'remove']);

Route::get('/catalog', function (Request $request) {
    //todo move this to CatalogController
    //todo don't return product with quantity 0
    $query = Product::query();

    if ($request->has('category')) {
        $query->where('category_id',
            Category::query()->where('name', $request->query('category'))->first()->id);
    }

    if ($request->has('text')) {
        //todo split string by _ to search for name with multiple words in it
        $query->where('name', 'like', '%' . $request->query('text') . '%');
    }

    if ($request->has('sort')) {
        $sortBy = $request->query('sort');
        switch ($sortBy) {
            case 'cheap':
                $query->orderBy('price');
                break;
            case 'expensive':
                $query->orderByDesc('price');
                break;
            case 'novelty':
                $query->orderByDesc('id');
                break;
        }
    }

    return new ProductCollection($query->paginate(env('PAGE_SIZE')));
});

Route::get('/categories', function () {
    return new CategoryCollection(Category::all());
});


