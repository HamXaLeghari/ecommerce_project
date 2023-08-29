<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function add(Request $request){

    $request->input('user_id');
        // $user = Auth::user();
        $order = new Order();

        $order->user_id = $request->input('user_id');
        $order->status= $request->input('status');
        $order["total amount"]= $request->input('total_amount');

        $order->save();


        return response()->json(['message' => 'Order placed successfully.']);


  }
  public function remove(Request $request){

        // $user = Auth::user();
        $order =Order::find($request->input('id')) ;
        $order->productOrders()->delete();

        $order->delete();


        return response()->json(['message' => 'Order removed successfully.']);


  }
}
