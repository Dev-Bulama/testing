<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NumberController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/numbers', [NumberController::class, 'index']);
    Route::get('/numbers/{phoneNumber}/messages', [MessageController::class, 'index']);
});

Route::post('/webhooks/sms/{provider}', [WebhookController::class, 'handleIncoming'])
    ->name('webhooks.sms');
