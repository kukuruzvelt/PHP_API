<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get(Request $request){
        if ($request->has('id') && $request->id != '') {
            if(Product::whereId($request->id)->exists()){
                return new ProductResource(Product::whereId($request->id)->first());
            }
            else throw new \Exception('No product with such id');
        }
        else throw new \Exception('No parameters were passed');
    }
}
