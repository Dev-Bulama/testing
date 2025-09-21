<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\PhoneNumber;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'numbersCount' => PhoneNumber::count(),
            'activeRentals' => PhoneNumber::whereNotNull('rented_by')->count(),
            'customersCount' => User::where('role', User::ROLE_CUSTOMER)->count(),
            'messagesCount' => Message::count(),
            'revenue' => Transaction::where('type', Transaction::TYPE_DEBIT)->sum('amount'),
            'latestTransactions' => Transaction::latest()->take(10)->with('user')->get(),
        ]);
    }
}
