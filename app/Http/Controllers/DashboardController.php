<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::with([
            'details.product'
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalOrders = $transactions->count();

        $pendingOrders = $transactions
            ->where('status', 'pending')
            ->count();

        $completedOrders = $transactions
            ->where('status', 'completed')
            ->count();

        return view('dashboard.index', compact(
            'user',
            'transactions',
            'totalOrders',
            'pendingOrders',
            'completedOrders'
        ));
    }
}