<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\Payments\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $gatewayManager,
        protected WalletService $walletService
    ) {
    }

    public function wallet(): View
    {
        $user = auth()->user();

        return view('customer.wallet', [
            'transactions' => $user->transactions()->latest()->paginate(),
            'balance' => $user->wallet_balance,
        ]);
    }

    public function fundWallet(): RedirectResponse
    {
        $amount = (float) request('amount', 0);
        if ($amount <= 0) {
            return back()->withErrors('Invalid amount');
        }

        $reference = $this->gatewayManager->charge(auth()->user(), $amount, [
            'description' => 'Wallet funding',
        ]);

        $this->walletService->credit(auth()->user(), $amount, [
            'reference' => $reference,
            'description' => 'Wallet funding',
            'gateway' => 'stripe',
        ]);

        return back()->with('status', 'Wallet funded successfully');
    }

    public function invoices(): View
    {
        return view('customer.invoices', [
            'transactions' => auth()->user()->transactions()->latest()->get(),
        ]);
    }
}
