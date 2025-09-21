<?php

namespace App\Services\Providers;

use App\Models\PhoneNumber;
use App\Models\Provider;

interface ProviderClientInterface
{
    /**
     * Fetch available numbers from remote provider.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchNumbers(Provider $provider, array $filters = []): array;

    /**
     * Reserve or rent a number via provider API.
     */
    public function rentNumber(Provider $provider, PhoneNumber $phoneNumber, array $options = []): void;

    /**
     * Release number on provider side.
     */
    public function releaseNumber(Provider $provider, PhoneNumber $phoneNumber): void;
}
