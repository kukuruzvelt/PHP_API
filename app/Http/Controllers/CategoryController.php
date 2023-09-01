<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'category')]
class CategoryController extends Controller
{
    #[OA\Get(path: '/api/category', description: '', tags: ['category'])]
    #[OA\QueryParameter(name: 'id', description: 'ID of category', required: true, allowEmptyValue: false)]
    #[OA\Response(response: 200, description: 'OK',)]
    #[OA\Response(response: 400, description: 'No parameters were passed',)]
    #[OA\Response(response: 404, description: 'No category with such id',)]
    public function get(Request $request)
    {
        if ($request->has('id') && $request->id != '') {
            if (Category::whereId($request->id)->exists()) {
                return new CategoryResource(Category::whereId($request->id)->first());
            }
            else return response()->json(data: ['error_message' => trans('messages.no_category_with_such_id')], status: 404);
        }
        else return response()->json(data: ['error_message' => trans('messages.no_params_passed')], status: 400);
    }

    #[OA\Get(path: '/api/category/all', description: 'List of all categories', tags: ['category'])]
    #[OA\Response(response: 200, description: 'OK',)]
    public function getAll(Request $request): CategoryCollection
    {
        return new CategoryCollection(Category::all());
    }
}
