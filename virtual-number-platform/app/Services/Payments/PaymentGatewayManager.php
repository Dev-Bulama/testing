<?php

namespace App\Services\Payments;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class PaymentGatewayManager
{
    /**
     * @var array<string, class-string<PaymentGatewayInterface>>
     */
    protected array $gateways = [
        'stripe' => StripePaymentGateway::class,
    ];

    public function extend(string $name, string $gatewayClass): void
    {
        $this->gateways[$name] = $gatewayClass;
    }

    public function charge(User $user, float $amount, array $meta = [], string $gateway = 'stripe'): string
    {
        $gateway = strtolower($gateway);
        $class = Arr::get($this->gateways, $gateway);

        if (! $class) {
            throw new \InvalidArgumentException("Gateway {$gateway} is not supported");
        }

        /** @var PaymentGatewayInterface $instance */
        $instance = App::make($class);
        $instance->initialize($meta['config'] ?? []);

        return $instance->charge($user, $amount, $meta);
    }
}
