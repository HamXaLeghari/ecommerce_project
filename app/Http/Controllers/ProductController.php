<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class ProductController extends Controller
{

    public function findAll(){
        return response(Product::query()->with(["category"])->get(),200);
    }


    public function findOne($id){

        try {

            $input = Validator::make(["id"=>$id],["id"=>"required|numeric"])->validate();

            return response(Product::query()->findOrFail($input["id"]),200);

        }

        catch (ValidationException|ModelNotFoundException|Exception $exception){
            return response(["message"=>$exception->getMessage()],400);
        }

    }

    public function findByCategory($categoryName)
    {
        try {

            $productsInCategory = Product::whereHas('category', function (Builder $query) use ($categoryName) {
                $query->where('name', $categoryName);
            })->get();

            return response($productsInCategory, 200);
        } catch (ValidationException | ModelNotFoundException | Exception $exception) {
            return response(["message" => $exception->getMessage()], 400);
        }
    }

    public function searchByTitle(Request $request){

        try {

            $input = $this->validate($request, ["q" => "required|string"]);

            $emp = "%";

            return response(Product::query()->select()->where("product.title","LIKE",$emp.$input["q"].$emp)->get(),200);

        }

        catch (ValidationException|ModelNotFoundException|Exception $exception){
            return response(["message"=>$exception->getMessage()],400);
        }

    }


    public function add(Request $request){

        try {

            $input = $request->validate([
                "title" => "required|string",
                "description" => "required|string",
                "image" => "required|image|mimes:jpeg,jpg,png",
                "price"=>"required|numeric",
                "stock_quantity"=>"required|numeric",
                "is_featured" => "sometimes|boolean",
                "is_available"=>"sometimes|boolean",
                "category_id"=>"required|numeric"
            ]);

            $productDir = "public/product_images";


            if (!Storage::exists($productDir)) {
                Storage::makeDirectory($productDir);
            }

            $imagePath = Storage::putFile($productDir,$input["image"],'public');

            unset($input["image"]);

            $product = new Product();

            $product->fill($input);
            $product->fill(["image" => Storage::url($imagePath)]);

            $product->save();

            return response(["message"=>"Entity Created Successfully!"],201);
        }


        catch (ValidationException|Exception $exception){

            return response(["message"=>$exception->getMessage()],400);
        }
    }

    public function update(Request $request)
    {

        try {

            $input = $request->validate([
                "id"=>"required|numeric",
                "title" => "required|string",
                "description" => "required|string",
                "image" => "sometimes|image|mimes:jpeg,jpg,png",
                "price"=>"required|numeric",
                "stock_quantity"=>"required|numeric",
                "is_featured" => "required|boolean",
                "is_available"=>"required|boolean",
                "category_id"=>"required|numeric"
            ]);

            $product = Product::query()->findOrFail($input["id"]);


            if ($request->has("image")) {

                $relativePath = explode("/storage/",$product->image)[1];


                Storage::disk('public')->delete($relativePath);


                $productDir = "public/product_images";

                if (!Storage::exists($productDir)) {
                    Storage::makeDirectory($productDir);
                }

                $imagePath = Storage::putFile($productDir, $input["image"], 'public');
                $product->fill(["image" => Storage::url($imagePath)]);
            }


            unset($input["image"]);
            $product->fill($input);
            $product->save();

            return response(["message"=>"Entity Updated Successfully!"],200);
        }

        catch (ValidationException|ModelNotFoundException|Exception $exception){
            return response(["message"=>$exception->getMessage(),"stack trace"=>$exception->getTrace()],400);
        }

    }

    public function delete(Request $request){

        try {

            $input = $request->validate(["id" => "required|numeric"]);

            $product = Product::query()->findOrFail($input["id"]);

            $relativePath = explode("/storage/",$product->image)[1];

            if (Storage::disk('public')->exists($relativePath)){
                Storage::disk('public')->delete($relativePath);
            }

            $product->delete();

            return response(["message"=>"Entity Deleted Successflly!"],200);

        }

        catch (ValidationException|Exception $exception){
            return response(["message"=>$exception->getMessage()],400);
        }

    }

}
