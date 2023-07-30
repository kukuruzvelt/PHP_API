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
        $cart = Cart::whereUserId($user->id)->paginate(env('PAGE_SIZE'));
        $totalPrice = Cart::whereUserId($user->id)
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->selectRaw('SUM(products.price * cart.quantity) as total_price')
            ->value('total_price');

        return response()->json([
            'data' => [
                'cart' => new CartCollection($cart),
                'total_price' => $totalPrice,
                'last_page' => $cart->lastPage(),
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
            if (Cart::whereProductId($product_id)->whereUserId($user_id)->exists()) {
                $cart = Cart::whereProductId($product_id)->whereUserId($user_id)->first();;
            } else {
                if (Cart::whereUserId($user_id)->count() < env('MAX_CART_SIZE')) {
                    $cart = new Cart();
                    $cart->product_id = $product_id;
                    $cart->quantity = 0;
                    $cart->user_id = $user_id;
                    $cart->save();
                } else {
                    throw new \Exception('The limit of products in the cart has been exceeded');
                }
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
