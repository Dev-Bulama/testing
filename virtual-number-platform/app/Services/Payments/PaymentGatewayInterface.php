<?php

namespace App\Services\Payments;

use App\Models\User;

interface PaymentGatewayInterface
{
    public function initialize(array $config = []): void;

    public function charge(User $user, float $amount, array $meta = []): string;
}
