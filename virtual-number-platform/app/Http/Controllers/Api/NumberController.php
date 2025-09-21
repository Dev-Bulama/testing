<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class NumberController extends Controller
{
    public function index(): JsonResponse
    {
        $numbers = auth()->user()->phoneNumbers()->with('provider')->get();

        return response()->json([
            'data' => $numbers,
        ]);
    }
}
