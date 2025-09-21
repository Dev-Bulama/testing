<?php

namespace App\Services\Payments;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected array $config = [];

    public function initialize(array $config = []): void
    {
        $this->config = $config;
    }

    public function charge(User $user, float $amount, array $meta = []): string
    {
        $reference = $meta['reference'] ?? Str::uuid()->toString();

        Log::info('Simulating Stripe charge', [
            'user' => $user->id,
            'amount' => $amount,
            'reference' => $reference,
        ]);

        return $reference;
    }
}
