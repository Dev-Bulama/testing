<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhoneNumber;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function index(PhoneNumber $phoneNumber): JsonResponse
    {
        abort_unless($phoneNumber->rented_by === auth()->id(), 403);

        $messages = $phoneNumber->messages()->latest()->get();

        return response()->json(['data' => $messages]);
    }
}
