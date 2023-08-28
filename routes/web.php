<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/category",[CategoryController::class,"findAll"]);

Route::post("/category/add",[CategoryController::class,"add"]);

Route::post("/category/update",[CategoryController::class,"update"]);

Route::delete("/category/delete",[CategoryController::class,"delete"]);


Route::get("/products",[ProductController::class,"findAll"]);
Route::get("/products/category/{categoryName}",[ProductController::class,"findByCategory"]);

Route::get("/products/search",[ProductController::class, "searchByTitle"]);

Route::post("/products/add",[ProductController::class,"add"]);

Route::post("/products/update",[ProductController::class,"update"]);

Route::delete("/products/delete",[ProductController::class,"delete"]);
