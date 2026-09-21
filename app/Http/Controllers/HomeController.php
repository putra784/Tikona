<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // 1. Data Best Sellers (Query lama Anda)
        $bestSellers = Product::query()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withSum('transactionDetails', 'quantity')
            ->where('is_available', true)
            ->orderByDesc('transaction_details_sum_quantity')
            ->take(4)
            ->get();

        // 2. Data Reviews untuk Section Testimonial (Query baru)
        $reviews = Review::query()
            ->with(['user:id,name', 'product:id,name'])
            ->latest()
            ->take(3)
            ->get();

        // 3. Kirim kedua variabel ($bestSellers dan $reviews) ke view
        return view('index', compact('bestSellers', 'reviews'));
    }
}