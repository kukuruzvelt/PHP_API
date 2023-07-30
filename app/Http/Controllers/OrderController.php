<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class OrderController extends Controller
{
    public function create(Request $request): void
    {
        DB::transaction(function () use ($request) {
            if ($request->has('city') && $request->has('date')) {
                $user = $request->user();

                $total_price = Cart::whereUserId($user->id)
                    ->join('products', 'cart.product_id', '=', 'products.id')
                    ->selectRaw('SUM(products.price * cart.quantity) as total_price')
                    ->value('total_price');

                if ($user->money < $total_price) {
                    throw new \Exception('Not enough money to buy this products');
                } else {
                    $user->money = $user->money - $total_price;
                    $user->save();
                }

                $order = new Order();
                $order->user_id = $user->id;
                $order->city = $request->city;
                $order->date = $request->date;
                $order->status = 'IN PROGRESS';
                $order->save();

                $cart = Cart::whereUserId($user->id)->get();
                foreach ($cart as $cart_instance) {
                    $orderProduct = new OrderProduct();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $cart_instance->product_id;
                    $orderProduct->quantity = $cart_instance->quantity;
                    $orderProduct->save();
                }

                Cart::whereUserId($user->id)->delete();
            } else {
                throw new \Exception('Some of parameters are missing');
            }
        });
    }

    public function cancel(Request $request)
    {
        if ($request->has('order_id')) {
            $order = Order::whereId($request->order_id)->first();
            $order->status = "CANCELED";
            $order->save();
        } else {
            throw new \Exception('Some of parameters are missing');
        }
    }

    public function getAll(Request $request): OrderCollection
    {
        $user = $request->user();
        return new OrderCollection(Order::whereUserId($user->id)->paginate(env('PAGE_SIZE')));
    }

    public function getProducts(Request $request)
    {
        if ($request->has('order_id')) {
            $products = Product::join('order_product', 'products.id', '=', 'order_product.product_id')
                ->where('order_product.order_id', $request->order_id)
                ->select('products.*', 'order_product.quantity')
                ->get();

            return new ProductCollection($products);
        } else {
            throw new \Exception('Some of parameters are missing');
        }
    }
}
