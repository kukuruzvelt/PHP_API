<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get(Request $request){
        if ($request->has('id') && $request->id != '') {
            if(Category::whereId($request->id)->exists()){
                return new CategoryResource(Category::whereId($request->id)->first());
            }
            else throw new \Exception('No category with such id');
        }
        else throw new \Exception('No parameters were passed');
    }

    public function getAll(Request $request): CategoryCollection
    {
        return new CategoryCollection(Category::all());
    }
}
