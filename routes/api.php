<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('cart')->group(function () {
    Route::put('{uuid}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('{uuid}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::post('order/{uuid}', OrderController::class)->name('order.place');

