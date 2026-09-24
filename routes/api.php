<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/register', [
    AuthController::class,
    'register',
])->middleware('throttle:5,1');

Route::post('/login', [
    AuthController::class,
    'login',
])->middleware('throttle:5,1');


/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/products', [
    ProductController::class,
    'index',
]);

Route::get('/reviews/summary', [
    ReviewController::class,
    'summary'
]);

Route::get('/products/{product}', [
    ProductController::class,
    'show',
]);


/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

Route::get('/categories', [
    CategoryController::class,
    'index',
]);

Route::get('/categories/{category}', [
    CategoryController::class,
    'show',
]);


/*
|--------------------------------------------------------------------------
| Product Reviews - Public
|--------------------------------------------------------------------------
*/

Route::get('/products/{product}/reviews', [
    ReviewController::class,
    'index',
]);


/*
|--------------------------------------------------------------------------
| Midtrans Notification
|--------------------------------------------------------------------------
|
| Endpoint ini dipanggil langsung oleh Midtrans.
| Jangan menggunakan auth:sanctum.
|
*/

Route::post('/payments/midtrans/notification', [
    PaymentController::class,
    'notification',
]);


/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ]);

    Route::get('/me', [
        AuthController::class,
        'me',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        UserController::class,
        'show',
    ]);

    Route::put('/profile', [
        UserController::class,
        'update',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Transactions - Customer
    |--------------------------------------------------------------------------
    */

    Route::get('/transactions', [
        TransactionController::class,
        'index',
    ]);

    Route::post('/transactions', [
        TransactionController::class,
        'store',
    ]);

    Route::get('/transactions/{transaction}', [
        TransactionController::class,
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Payments - Customer
    |--------------------------------------------------------------------------
    */

    Route::post('/payments', [
        PaymentController::class,
        'store',
    ]);

    Route::get('/payments/{payment}', [
        PaymentController::class,
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Reviews - Customer
    |--------------------------------------------------------------------------
    */

    Route::post('/products/{product}/reviews', [
        ReviewController::class,
        'store',
    ]);

    Route::put('/reviews/{review}', [
        ReviewController::class,
        'update',
    ]);

    Route::delete('/reviews/{review}', [
        ReviewController::class,
        'destroy',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Admin API
    |--------------------------------------------------------------------------
    |
    | Role yang digunakan:
    | admin
    |
    */

    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Product Management
            |--------------------------------------------------------------------------
            */

            Route::post('/products', [
                ProductController::class,
                'store',
            ]);

            Route::put('/products/{product}', [
                ProductController::class,
                'update',
            ]);

            Route::delete('/products/{product}', [
                ProductController::class,
                'destroy',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Category Management
            |--------------------------------------------------------------------------
            */

            Route::post('/categories', [
                CategoryController::class,
                'store',
            ]);

            Route::put('/categories/{category}', [
                CategoryController::class,
                'update',
            ]);

            Route::delete('/categories/{category}', [
                CategoryController::class,
                'destroy',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Transaction Management
            |--------------------------------------------------------------------------
            */

            Route::get('/transactions', [
                TransactionController::class,
                'adminIndex',
            ]);

            Route::get('/transactions/{transaction}', [
                TransactionController::class,
                'adminShow',
            ]);

            Route::put('/transactions/{transaction}/status', [
                TransactionController::class,
                'updateStatus',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Payment Management
            |--------------------------------------------------------------------------
            */

            Route::get('/payments/{payment}', [
                PaymentController::class,
                'adminShow',
            ]);
        });
});