<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
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

// Route::post('/add_cart/{id}', [CartController::class,'add_cart']);
// Route::get('/show_cart', [CartController::class,'show_cart']);
// Route::get('/remove_cart/{id}', [CartController::class,'remove_cart']);
// Route::get('/cash_order', [CartController::class,'cash_order']);
// Route::get('/stripe/{totalprice}', [CartController::class,'stripe']);

Route::post('add',[OrderController::class,'add']);
Route::delete('remove',[OrderController::class,'remove']);
