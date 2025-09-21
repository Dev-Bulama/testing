<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PhoneNumber;
use App\Services\Payments\WalletService;
use App\Services\Providers\ProviderManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PhoneNumberController extends Controller
{
    public function __construct(
        protected WalletService $walletService,
        protected ProviderManager $providerManager
    ) {
    }

    public function index(): View
    {
        return view('customer.numbers.index', [
            'availableNumbers' => PhoneNumber::available()->with('provider')->get(),
            'activeNumbers' => auth()->user()->phoneNumbers()->with('provider')->get(),
        ]);
    }

    public function rent(PhoneNumber $phoneNumber): RedirectResponse
    {
        $user = auth()->user();

        if ($phoneNumber->status !== PhoneNumber::STATUS_AVAILABLE) {
            return back()->withErrors('Number is not available');
        }

        try {
            \DB::transaction(function () use ($user, $phoneNumber) {
                $this->walletService->debit($user, (float) $phoneNumber->cost, [
                    'description' => 'Number rental',
                    'phone_number' => $phoneNumber->number,
                ]);

                $phoneNumber->forceFill([
                    'status' => PhoneNumber::STATUS_ASSIGNED,
                    'rented_by' => $user->id,
                    'rented_at' => now(),
                    'expires_at' => now()->addDays(7),
                ])->save();
            });

            if ($phoneNumber->provider) {
                $this->providerManager->rentNumber($phoneNumber->provider, $phoneNumber);
            }
        } catch (\Throwable $exception) {
            return back()->withErrors($exception->getMessage());
        }

        return back()->with('status', 'Number rented successfully');
    }

    public function extend(PhoneNumber $phoneNumber): RedirectResponse
    {
        $user = auth()->user();

        if ($phoneNumber->rented_by !== $user->id) {
            abort(403);
        }

        $duration = (int) request('days', 7);
        $cost = (float) $phoneNumber->cost * ($duration / 7);

        try {
            $this->walletService->debit($user, $cost, [
                'description' => 'Rental extension',
                'phone_number' => $phoneNumber->number,
            ]);

            $phoneNumber->expires_at = ($phoneNumber->expires_at ?? now())->addDays($duration);
            $phoneNumber->save();
        } catch (\Throwable $exception) {
            return back()->withErrors($exception->getMessage());
        }

        return back()->with('status', 'Rental extended');
    }

    public function release(PhoneNumber $phoneNumber): RedirectResponse
    {
        $user = auth()->user();

        if ($phoneNumber->rented_by !== $user->id) {
            abort(403);
        }

        if ($phoneNumber->provider) {
            try {
                $this->providerManager->releaseNumber($phoneNumber->provider, $phoneNumber);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        $phoneNumber->forceFill([
            'status' => PhoneNumber::STATUS_AVAILABLE,
            'rented_by' => null,
            'rented_at' => null,
            'expires_at' => null,
        ])->save();

        return back()->with('status', 'Number released');
    }
}
