<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        $cart = Cart::whereUserId($user->id)->get();
        $total_price = 0;
        foreach ($cart as $cart_instance) {
            $total_price += $cart_instance->product->price * $cart_instance->quantity;
        }

        return response()->json([
            'data' => [
                'cart' => new CartCollection($cart),
                'total_price' => $total_price,
            ]
        ]);
    }

    public function add(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $user_id = $request->user()->id;
            $product_id = $request->product_id;

            $product = Product::whereId($product_id)->first();
            $cart = Cart::whereProductId($product_id)->whereUserId($user_id)->first();

            $product_quantity = $product->quantity;
            if ($product_quantity < 1) {
                throw new \Exception('Product is out of stock');
            } else {
                $product->quantity = $product_quantity - 1;
                $cart->quantity = $cart->quantity + 1;
                $product->save();
                $cart->save();
            }
        });
    }

    public function remove(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $user_id = $request->user()->id;
            $product_id = $request->product_id;

            $product = Product::whereId($product_id)->first();
            $cart = Cart::whereUserId($user_id)::whereProductId($product_id)->first();

            if ($cart->quantity < 1) {
                throw new \Exception('This product is no longer os your cart');
            } else {
                $product->quantity = $product->quantity + 1;
                $cart->quantity = $cart->quantity - 1;
                $product->save();
                $cart->save();
            }
        });
    }
}
