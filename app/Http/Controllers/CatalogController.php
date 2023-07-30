<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function get(Request $request): ProductCollection
    {
        $query = Product::query()->where('quantity', '>', 0);

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
