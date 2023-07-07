<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartCollection;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        $cart = Cart::whereUserId($user->id)->get();
        $total_price = 0;
        foreach ($cart as $cart_instance){
            $total_price += $cart_instance->product->price * $cart_instance->quantity;
        }

        return response()->json([
            'products' => new CartCollection($cart),
            'total_price' => $total_price,
        ]);
    }
}
