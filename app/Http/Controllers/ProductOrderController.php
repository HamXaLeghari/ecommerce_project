<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{

    public function findByOrder(Request $request){

        $input = $this->validate($request,["order_id"=>"required|numeric"]);

      //  $productOrders = ProductOrder::query()->select(["*"])->with(["product","order"])->where("order_id","=",$input["order_id"])

            $productOrders = Order::query()
                ->findOrFail($input["id"])
                ->productOrders()
                ->with(["order","product"])
                ->get();

         return response($productOrders,200);
    }
    public function add(Request $request){

      $input = $this->validate($request,[
           "product_id"=>"required|numeric",
           "order_id"=>"required|numeric",
            "quantity"=>"required|numeric"
        ]);

      $order = Order::query()->findOrFail($input["order_id"]);
      $product = Product::query()->findOrFail($input["product_id"],"price");

      $productOrder = new ProductOrder();

      $productOrder->fill($input);

      $productOrder->sub_total = $product->price * $input["quantity"];

      $productOrder->save();

     $order["total amount"] = $order->productOrders()->sum("sub_total");

     $order->update();

      return response(["message"=>"deletion successful"],201);

    }

    public function remove(Request $request){

        $input = $this->validate($request,["id"=>"required|numeric"]);

        $productOrder = ProductOrder::query()->findOrFail($input["id"]);

        $productOrder->deleteOrFail();

        return response(["message"=>"Deletion Successful"],200);
    }

    public function updateQuantity(Request $request){

        $input = $this->validate($request,[
            "id"=>"required|numeric",
            "quantity"=>"required|numeric",
        ]);

        $productOrder = ProductOrder::query()->findOrFail($input["id"]);

        $productOrder->fill($input);

        $productOrder->quantity = $input["quantity"];


      $productOrder->sub_total = $productOrder->product()->price * $input["quantity"];

      $productOrder->update();

      $order = $productOrder->order();

      $order["total amount"] = $order->productOrders()->sum("sub_total");

      $order->update();

     return response(["message"=>"Update Successful"],200);

    }


}
