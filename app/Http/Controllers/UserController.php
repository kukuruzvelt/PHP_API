<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'user')]
class UserController extends Controller
{
    #[OA\Get(path: '/api/user', description: 'Returns currently logged user', security: ["sanctum"], tags: ['user'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function get(Request $request)
    {
        return $request->user();
    }

    #[OA\Post(path: '/api/user/pay', description: '', security: ["sanctum"], tags: ['user'])]
    #[OA\RequestBody(content: [
        new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(properties: [
            new OA\Property(property: 'money_amount', description: 'The money amount by which the balance is replenished'
                , type: 'string'),
        ]
            , example: [
                '{"money_amount": "10000"}',
            ],))
    ])]
    #[OA\Response(response: 200, description: 'OK')]
    #[OA\Response(response: 400, description: 'No parameters were passed')]
    public function pay(Request $request){
        if($request->has('money_amount')){
            $user = User::whereId($request->user()->id)->first();
            $money = $user->money;
            $user->money =  $money + $request->money_amount;
            $user->save();
        }
        else return response()->json(data: ['error_message' => trans('messages.no_params_passed')], status: 400);
    }
}
