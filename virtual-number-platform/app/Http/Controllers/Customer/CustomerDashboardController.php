<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $numbers = $user->phoneNumbers()->with(['messages' => function ($query) {
            $query->latest()->take(5);
        }])->withCount('messages')->get();

        return view('customer.dashboard', [
            'numbers' => $numbers,
            'transactions' => $user->transactions()->latest()->take(10)->get(),
            'balance' => $user->wallet_balance,
        ]);
    }
}
