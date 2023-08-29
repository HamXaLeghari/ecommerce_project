<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Nette\Schema\ValidationException;

class CategoryController extends Controller
{
    public function findAll(){
        return response(Category::all(),200);
    }

    public function add(Request $request){

        try {

            $input = $request->validate([
                "name" => "required|string",
                "image" => "required|image|mimes:jpeg,jpg,png",
                "description" => "required|string",
                "is_featured" => "sometimes|boolean",
                "is_active" => "sometimes|boolean"
            ]);

            $categoryDir = "public/category_images";


            if (!Storage::exists($categoryDir)) {
               Storage::makeDirectory($categoryDir);
            }

            $imagePath = Storage::putFile($categoryDir,$input["image"],'public');

            unset($input["image"]);

            $category = new Category();

            $category->fill($input);
            $category->fill(["image_path" => Storage::url($imagePath)]);

            $category->save();

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
                "name" => "required|string",
                "image" => "sometimes|image|mimes:jpeg,jpg,png",
                "description" => "required|string",
                "is_featured" => "required|boolean",
                "is_active" => "required|boolean"
            ]);

            $category = Category::query()->findOrFail($input["id"]);


            if ($request->has("image")) {

                $relativePath = explode("/storage/",$category->image_path)[1];

                Storage::disk('public')->delete($relativePath);

                $categoryDir = "public/category_images";

                if (!Storage::exists($categoryDir)) {
                    Storage::makeDirectory($categoryDir);
                }

                $imagePath = Storage::putFile($categoryDir, $input["image"], 'public');
                $category->fill(["image_path" => Storage::url($imagePath)]);
        }

            $category->fill($input);
            $category->save();

            return response(["message"=>"Entity Updated Successfully!"],200);
        }

        catch (ValidationException|ModelNotFoundException|Exception $exception){
            return response(["message"=>$exception->getMessage(),"stack teace"=>$exception->getTrace()],400);
        }


    }

    public function delete(Request $request){

        try {

            $input = $request->validate(["id" => "required|numeric"]);

            $category = Category::query()->findOrFail($input["id"]);

            $relativePath = explode("/storage/",$category->image_path)[1];

            if (Storage::disk('public')->exists($relativePath)){
                Storage::disk('public')->delete($relativePath);
            }


            $category->products()->delete();

            $category->delete();

            return response(["message"=>"Entity Deleted Successflly!"],200);

        }

        catch (ValidationException|Exception $exception){
            return response(["message"=>$exception->getMessage()],400);
        }

    }

}
