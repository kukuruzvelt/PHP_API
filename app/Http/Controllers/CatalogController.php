<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function get(Request $request): ProductCollection
    {
        //todo move this to CatalogController
        //todo don't return product with quantity 0
        $query = Product::query();

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('text') && $request->text != '') {
            //todo split string by _ to search for name with multiple words in it
            $query->where('name', 'like', '%' . $request->text . '%');
        }

        if ($request->has('sort') && $request->sort != '') {
            $sortBy = $request->sort;
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
    }
}
