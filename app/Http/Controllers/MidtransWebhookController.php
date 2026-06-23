<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\MidtransWebhookService;

class MidtransWebhookController extends Controller
{
    public function __construct(protected MidtransWebhookService $service)
    {
    }

    public function handle(Request $request): JsonResponse
    {
        $payment = $this->service->process($request->all());

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook received',
            'data' => $payment,
        ]);
    }
}
