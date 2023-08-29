<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'product')]
class ProductController extends Controller
{
    #[OA\Get(path: '/api/product', description: 'Returns product with given ID', tags: ['product'])]
    #[OA\QueryParameter(name: 'id', description: 'ID of product', required: true, allowEmptyValue: false)]
    #[OA\Response(response: 200, description: 'OK')]
    #[OA\Response(response: 500, description: 'No product with such id')]
    public function get(Request $request): ProductResource
    {
        if ($request->has('id') && $request->id != '') {
            if(Product::whereId($request->id)->exists()){
                return new ProductResource(Product::whereId($request->id)->first());
            }
            else throw new \Exception(trans('messages.no_product_with_such_id'));
        }
        else throw new \Exception(trans('messages.no_params_passed'));
    }
}
