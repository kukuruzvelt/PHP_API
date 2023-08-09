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
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'order')]
class OrderController extends Controller
{
    #[OA\Post(path: '/api/order/create', description: 'Endpoint for creating order for logged user'
        , security: ["sanctum"], tags: ['order'])]
    #[OA\RequestBody(content: [
        new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(properties: [
            new OA\Property(property: 'city', description: 'City for products to be delivered to', type: 'string'),
            new OA\Property(property: 'date', description: 'Date of delivery', type: 'string'),
        ]
            , example: [
                '{"city": "Kyiv", "date": "2023-07-28"}',
            ],))
    ])]
    #[OA\Response(response: 200, description: 'OK')]
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

    #[OA\Post(path: '/api/order/cancel', description: 'Endpoint for cancelling logged user\'s order', security: ["sanctum"]
        , tags: ['order'])]
    #[OA\RequestBody(content: [
        new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(properties: [
            new OA\Property(property: 'order_id', description: 'ID of order to be canceled', type: 'string'),
        ]
            , example: [
                '{"order_id": "1"}',
            ],))
    ])]
    #[OA\Response(response: 200, description: 'OK')]
    public function cancel(Request $request)
    {
        //todo create error for case, when there is no order with given id and add it to the docs
        if ($request->has('order_id')) {
            $order = Order::whereId($request->order_id)->first();
            $order->status = "CANCELED";
            $order->save();
        } else {
            throw new \Exception('Some of parameters are missing');
        }
    }

    #[OA\Get(path: '/order/all', description: 'List of paginated orders for logged user', security: ["sanctum"]
        , tags: ['order'])]
    #[OA\QueryParameter(name: 'page', description: 'Number of page for paginated list of products in cart',
        required: true, allowEmptyValue: false)]
    #[OA\Response(response: 200, description: 'OK')]
    public function getAll(Request $request): OrderCollection
    {
        $user = $request->user();
        return new OrderCollection(Order::whereUserId($user->id)->paginate(env('PAGE_SIZE')));
    }

    #[OA\Get(path: '/order/getProducts', description: 'List of products in the order of logged user', security: ["sanctum"]
        , tags: ['order'])]
    #[OA\QueryParameter(name: 'order_id', description: 'ID of chosen order', required: true, allowEmptyValue: false)]
    #[OA\Response(response: 200, description: 'OK')]
    public function getProducts(Request $request)
    {
        //todo create error for case, when there is no order with given id and add it to the docs
        //todo replace manual checks of request params with validate()
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
