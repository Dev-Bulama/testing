<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\PhoneNumber;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleIncoming(string $provider, Request $request): JsonResponse
    {
        if ($token = config('services.twilio.webhook_token')) {
            abort_unless(hash_equals($token, $request->query('token', '')), 403);
        }

        $payload = $request->all();
        $number = PhoneNumber::where('number', Arr::get($payload, 'To'))->first();

        if (! $number) {
            Log::warning('Received SMS for unknown number', ['payload' => $payload]);
            return response()->json(['status' => 'ignored']);
        }

        $message = $number->messages()->create([
            'from_number' => Arr::get($payload, 'From'),
            'body' => Arr::get($payload, 'Body'),
            'received_at' => now(),
            'status' => Message::STATUS_UNREAD,
            'payload' => $payload,
        ]);

        return response()->json(['status' => 'ok', 'id' => $message->id]);
    }
}
