<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add_cart(Request $request, $id)
    {

        if (Auth::id()) {

            $user = Auth::user();
            $product = Product::find($id);
            $cart = new Cart;
            $cart->name = $user->name;
            $cart->email = $user->email;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->user_id = $user->id;
            $cart->product_title = $product->title;

            if ($product->discount_price != null) {

                $cart->product_price = $product->discount_price * $request->quantity;
            } else {
                $cart->product_price = $product->price * $request->quantity;

            }
            $cart->product_image = $product->image;
            $cart->product_id = $product->id;
            $cart->quantity = $request->quantity;

            $cart->save();

            return redirect()->back();


        } else {
            return redirect('login');
        }
        // echo 'Hello '.$id;
    }

    public function show_cart()
    {

        if (Auth::id()) {

            $id = Auth::user()->id;

            $data = Cart::where('user_id', '=', $id)->get();
            foreach ($data as $data) {
                $order = new Order;
                $order->name = $data->name;
                $order->email = $data->email;
                $order->phone = $data->phone;
                $order->address = $data->address;
                $order->user_id = $data->user_id;

                $order->product_title = $data->product_title;
                $order->price = $data->price;
                $order->quantity = $data->quantity;
                $order->image = $data->image;
                $order->product_id = $data->product_id;

                $order->payment_status = 'cash on delivery';
                $order->delivery_status = 'processing';
                $order->save();

            }
            return redirect()->back();

        } else {
            return redirect('login');
        }
    }
    public function remove_cart($id)
    {

        $cart = cart::find($id);
        $cart->delete();

        return redirect()->back();
    }
    public function cash_order()
    {
        $user_id = Auth::user()->id;
        $data = Cart::where('user_id', '=', $user_id)->get();

        foreach ($data as $data) {
            $order = new Order;
            $order->name = $data->name;
            $order->email = $data->email;
            $order->phone = $data->phone;
            $order->address = $data->address;
            $order->user_id = $data->user_id;

            $order->product_title = $data->product_title;
            $order->price = $data->price;
            $order->quantity = $data->quantity;
            $order->image = $data->image;
            $order->product_id = $data->product_id;

            $order->payment_status = 'cash on delivery';
            $order->delivery_status = 'processing';
            $order->save();

            $cart = Cart::find($data->id);
            $cart->delete;
        }
        return redirect()->back();

    }

    public function stripe($totalprice)
    {
        // return "Stripe";
        return view('stripe');

    }
}