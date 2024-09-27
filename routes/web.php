<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::controller(ProductsController::class)->group(function(){

    Route::get('/','index')->name('products.index');
    Route::get('/products/create','create' )->name('products.create');
    Route::post('/products','store')->name('products.store');
    Route::get('/products/{product}/edit','edit' )->name('products.edit');
    Route::put('/products/{product}','update' )->name('products.update');
    Route::delete('/products/{product}','destroy' )->name('products.destroy');




});
