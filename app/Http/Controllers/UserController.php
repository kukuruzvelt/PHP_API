<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(Request $request)
    {
        return $request->user();
    }

    public function pay(Request $request): void
    {
        if($request->has('money_amount')){
            $user = User::whereId($request->user()->id)->first();
            $money = $user->money;
            $user->money =  $money + $request->money_amount;
            $user->save();
        }
        else {
            throw new \Exception('Some of parameters are missing');
        }
    }

}
