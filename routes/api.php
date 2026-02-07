<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;


Route::middleware(['setApiLocal'])->group(function () {
    Route::name('products.')->prefix('products')->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('show', 'show')->name('show');
        });
    });

    Route::name('authentication.')->prefix('authentication')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
            Route::post('logout', 'logout')->name('logout');
            Route::post('me', 'me')->name('logout');
        });
    });

    Route::middleware(['apiAuthenticate'])->group(function () {
        Route::name('order.')->prefix('order')->group(function () {
            Route::controller(OrderController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/show', 'show')->name('show');
                Route::post('/confirm', 'confirm')->name('confirm');
                Route::put('/update-item', 'updateItems')->name('updateItems');
                Route::post('pay', 'pay')->name('pay');
                Route::post('delete', 'delete')->name('delete');


            });
        });

        Route::name('payment.')->prefix('payment')->group(function () {
            Route::controller(PaymentController::class)->group(function () {
                Route::get('index', 'index')->name('payments');
                Route::get('/show', 'show')->name('show');
                Route::get('/by-order-id', 'byOrderID')->name('byOrderID');
                Route::post('success','success')->name('success');
                Route::post('cancel','cancel')->name('cancel');
            });
        });

    });



});


