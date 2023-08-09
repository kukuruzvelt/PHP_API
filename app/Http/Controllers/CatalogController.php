<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'catalog')]

class CatalogController extends Controller
{
    #[OA\Get(path: '/api/catalog', description: 'Returns paginated list of products and pagination metadata', tags: ['catalog'])]
    #[OA\QueryParameter(name: 'page', description: 'Number of page for paginated list of products', required: true, allowEmptyValue: false)]
    #[OA\QueryParameter(name: 'category', description: 'ID of the searched category', required: false, allowEmptyValue: true)]
    #[OA\QueryParameter(name: 'text', description: 'Name of product to search for', required: false, allowEmptyValue: true
        , examples: [
            new OA\Examples(example: 'One word', summary: 'Search for a product whose name contains a given word', value: 'product'),
            new OA\Examples(example: 'Multiple words',
                summary: 'Search for a product whose name contains a given words separated by \'_\'', value: 'product_1'),
        ])]
    #[OA\QueryParameter(name: 'sort', description: 'Sorting option', required: false, allowEmptyValue: true
        , examples: [
            new OA\Examples(example: 'Cheap', summary: 'Sorts products from cheap to expensive', value: 'cheap'),
            new OA\Examples(example: 'Expensive', summary: 'Sorts products from expensive to cheap', value: 'expensive'),
            new OA\Examples(example: 'Novelty', summary: 'Sorts products by newest', value: 'novelty'),
        ])]
    #[OA\Response(response: 200, description: 'OK')]
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
