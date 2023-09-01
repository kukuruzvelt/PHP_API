<?php

namespace App\Http\Controllers;

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
    #[OA\Response(response: 404, description: 'No product with such id')]
    #[OA\Response(response: 400, description: 'No parameters were passed',)]
    public function get(Request $request){
        if ($request->has('id') && $request->id != '') {
            if(Product::whereId($request->id)->exists()){
                return new ProductResource(Product::whereId($request->id)->first());
            }
            else return response()->json(data: ['error_message' => trans('messages.no_product_with_such_id')], status: 404);
        }
        else return response()->json(data: ['error_message' => trans('messages.no_params_passed')], status: 400);
    }
}
