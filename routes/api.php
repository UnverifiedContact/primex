<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('product', [ProductController::class, 'index'])->name('product_index');
Route::post('product', [ProductController::class, 'store'])->name('product_store');

Route::get('product/{code}', [ProductController::class, 'show'])->name('product_show');
Route::patch('product/{code}', [ProductController::class, 'update'])->name('product_update');
Route::delete('product/{code}', [ProductController::class, 'destroy'])->name('product_destroy');

Route::get('product/{code}/stock', [StockController::class, 'product_index'])->name('product_stock_index');
Route::post('product/{code}/stock', [StockController::class, 'store'])->name('stock_store');
