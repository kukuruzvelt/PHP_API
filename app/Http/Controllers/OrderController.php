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

class OrderController extends Controller
{
    public function create(Request $request): void
    {
        DB::transaction(function () use ($request) {
            if ($request->has('city') && $request->has('date')) {
                $user = $request->user();

                $cart = Cart::whereUserId($user->id)->get();
                $total_price = 0;
                foreach ($cart as $cart_instance) {
                    $total_price += $cart_instance->product->price * $cart_instance->quantity;
                }

                if($user->money < $total_price){
                    throw new \Exception('Not enough money to buy this products');
                }
                else{
                    $user->money = $user->money - $total_price;
                    $user->save();
                }

                $order = new Order();
                $order->user_id = $user->id;
                $order->city = $request->city;
                $order->date = $request->date;
                $order->status = 'IN PROGRESS';
                $order->save();

                foreach ($cart as $cart_instance){
                    $orderProduct = new OrderProduct();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $cart_instance->product_id;
                    $orderProduct->quantity = $cart_instance->quantity;
                    $orderProduct->save();
                }

                Cart::whereUserId($user->id)->delete();
            }
            else{
                throw new \Exception('Some of parameters are missing');
            }
        });
    }

    public function cancel(Request $request)
    {

    }

    public function getAll(Request $request): OrderCollection
    {
        $user = $request->user();
        return new OrderCollection(Order::whereUserId($user->id)->get());
    }

    public function get(Request $request): ProductCollection
    {
        if($request->has('order_id')){
            $order_product = OrderProduct::whereOrderId($request->order_id)->get();
            $products = [];
            foreach ($order_product as $order_product_instance){
                $products[] = Product::whereId($order_product_instance->product_id);
            }
            return new ProductCollection($products);
        }
        else{
            throw new \Exception('Some of parameters are missing');
        }
    }
}
