<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Webhook received',
            'data' => $request->all(),
        ]);
    }
}
