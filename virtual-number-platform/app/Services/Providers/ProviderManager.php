<?php

namespace App\Services\Providers;

use App\Models\PhoneNumber;
use App\Models\Provider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class ProviderManager
{
    /**
     * @var array<string, class-string<ProviderClientInterface>>
     */
    protected array $drivers = [
        'twilio' => TwilioProviderClient::class,
    ];

    public function extend(string $name, string $clientClass): void
    {
        $this->drivers[$name] = $clientClass;
    }

    public function client(Provider $provider): ProviderClientInterface
    {
        $driver = Arr::get($this->drivers, strtolower($provider->name));

        if (! $driver) {
            throw new InvalidArgumentException("Provider {$provider->name} is not supported.");
        }

        return App::make($driver);
    }

    public function fetchNumbers(Provider $provider, array $filters = []): array
    {
        return $this->client($provider)->fetchNumbers($provider, $filters);
    }

    public function rentNumber(Provider $provider, PhoneNumber $phoneNumber, array $options = []): void
    {
        $this->client($provider)->rentNumber($provider, $phoneNumber, $options);
    }

    public function releaseNumber(Provider $provider, PhoneNumber $phoneNumber): void
    {
        $this->client($provider)->releaseNumber($provider, $phoneNumber);
    }
}
