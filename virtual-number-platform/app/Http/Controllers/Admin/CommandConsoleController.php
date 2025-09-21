<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhoneNumber;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CommandConsoleController extends Controller
{
    public function execute(): RedirectResponse
    {
        $command = strtolower(trim(request('command')));
        $output = match ($command) {
            'status' => sprintf(
                'Numbers: %d | Customers: %d | Revenue: %s',
                PhoneNumber::count(),
                User::where('role', User::ROLE_CUSTOMER)->count(),
                Transaction::where('type', Transaction::TYPE_DEBIT)->sum('amount')
            ),
            'check' => $this->checkExpiringNumbers(),
            'terminate' => $this->releaseExpiredNumbers(),
            default => 'Unknown command',
        };

        return back()->with('console_output', $output);
    }

    protected function checkExpiringNumbers(): string
    {
        $count = PhoneNumber::whereNotNull('expires_at')
            ->where('expires_at', '<', now()->addDay())
            ->count();

        return "Numbers expiring in 24h: {$count}";
    }

    protected function releaseExpiredNumbers(): string
    {
        $released = PhoneNumber::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => PhoneNumber::STATUS_AVAILABLE,
                'rented_by' => null,
                'rented_at' => null,
                'expires_at' => null,
            ]);

        return "Released {$released} numbers.";
    }
}
