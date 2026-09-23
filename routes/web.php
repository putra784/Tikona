<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::get('/about', function() {
    return view('about');
});

Route::get('/product', [ProductController::class, 'page'])
    ->name('product');