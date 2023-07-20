<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Webmozart\Assert\Tests\StaticAnalysis\integer;

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
            $quantity_to_add = 1;
            if ($request->has('quantity')) {
                $quantity_to_add = $request->quantity;
            }

            $product = Product::whereId($product_id)->first();
            $cart = null;
            if (Cart::whereProductId($product_id)->whereUserId($user_id)->exists()) {
                $cart = Cart::whereProductId($product_id)->whereUserId($user_id)->first();;
            } else {
                $cart = new Cart();
                $cart->product_id = $product_id;
                $cart->quantity = 0;
                $cart->user_id = $user_id;
                $cart->save();
            }

            $product_quantity = $product->quantity;
            if ($product_quantity < $quantity_to_add) {
                throw new \Exception('Product is out of stock');
            } else {
                $product->quantity = $product_quantity - $quantity_to_add;
                $cart->quantity = $cart->quantity + $quantity_to_add;
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
            $cart = Cart::whereUserId($user_id)->whereProductId($product_id)->first();

            if ($cart->quantity < 1) {
                throw new \Exception('This product is no longer os your cart');
            } elseif ($cart->quantity == 1) {
                $product->quantity = $product->quantity + 1;
                $cart->delete();
            } else {
                $product->quantity = $product->quantity + 1;
                $cart->quantity = $cart->quantity - 1;
                $product->save();
                $cart->save();
            }
        });
    }
}
