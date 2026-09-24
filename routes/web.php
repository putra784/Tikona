<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])
    ->name('index');

Route::get('/about', function () {
    return view('about');
});

Route::get('/product', [ProductController::class, 'page'])
    ->name('product');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::prefix('order')->name('order.')->group(function () {

    Route::get('/', [OrderController::class, 'index'])
        ->name('index');

    Route::post('/type', [OrderController::class, 'setType'])
        ->name('type');

    Route::get('/products', [OrderController::class, 'products'])
        ->name('products');

    Route::post('/cart', [OrderController::class, 'addToCart'])
        ->name('cart.add');

    Route::put('/cart/{product}', [OrderController::class, 'updateCart'])
        ->name('cart.update');

    Route::delete('/cart/{product}', [OrderController::class, 'removeFromCart'])
        ->name('cart.remove');

    Route::get('/review', [OrderController::class, 'review'])
        ->name('review');

    Route::post('/confirm', [OrderController::class, 'confirm'])
        ->name('confirm');


    // Payment
    Route::middleware('auth')->group(function () {

        Route::get('/payment/{transaction}', [PaymentController::class, 'show'])
            ->name('payment');

        Route::post('/payment/{transaction}/process', [PaymentController::class, 'process'])
            ->name('payment.process');

        Route::get('/payment/{transaction}/status', [
            PaymentController::class,
            'checkStatus'
        ])->name('payment.status');
    });


    Route::get('/success/{transaction}', [OrderController::class, 'success'])
        ->name('success');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/test-email', function () {
    Mail::raw('Ini adalah email testing dari aplikasi Tikona.', function ($message) {
        $message
            ->to('putrayasa.id15@gmail.com')
            ->subject('Test Email Tikona');
    });

    return 'Email berhasil dikirim!';
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])
    ->name('otp.form');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
    ->name('otp.verify');

Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])
    ->name('otp.resend');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

});