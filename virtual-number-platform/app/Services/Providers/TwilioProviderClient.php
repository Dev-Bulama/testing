<?php

namespace App\Services\Providers;

use App\Models\PhoneNumber;
use App\Models\Provider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;

class TwilioProviderClient implements ProviderClientInterface
{
    public function fetchNumbers(Provider $provider, array $filters = []): array
    {
        $client = $this->makeClient($provider);
        $country = Arr::get($filters, 'country', $provider->configuration['country'] ?? 'US');
        $type = Arr::get($filters, 'type', 'local');

        try {
            $numbers = $client->availablePhoneNumbers($country)
                ->{$type}
                ->read([
                    'smsEnabled' => true,
                    'voiceEnabled' => false,
                ]);

            return collect($numbers)->map(function ($number) {
                return [
                    'number' => $number->phoneNumber,
                    'country' => $number->isoCountry,
                    'capabilities' => $number->capabilities,
                    'external_id' => $number->sid,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            Log::error('Twilio fetch numbers failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function rentNumber(Provider $provider, PhoneNumber $phoneNumber, array $options = []): void
    {
        $client = $this->makeClient($provider);

        try {
            $client->incomingPhoneNumbers->create([
                'phoneNumber' => $phoneNumber->number,
                'smsUrl' => url('/api/webhooks/sms/twilio').'?token='.config('services.twilio.webhook_token'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Twilio rent number failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function releaseNumber(Provider $provider, PhoneNumber $phoneNumber): void
    {
        $client = $this->makeClient($provider);

        if (! $phoneNumber->external_id) {
            return;
        }

        try {
            $client->incomingPhoneNumbers($phoneNumber->external_id)->delete();
        } catch (\Throwable $e) {
            Log::warning('Twilio release number failed', ['error' => $e->getMessage()]);
        }
    }

    protected function makeClient(Provider $provider): TwilioClient
    {
        $sid = $provider->api_key ?: config('services.twilio.sid');
        $token = $provider->api_secret ?: config('services.twilio.token');

        return new TwilioClient($sid, $token);
    }
}
