<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function create(Todo $todo): JsonResponse
    {
        $payment = $this->midtransService->createPayment($todo);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => [
                'id' => $payment->id,
                'todo_id' => $payment->todo_id,
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'snap_token' => $payment->snap_token,
            ]
        ]);
    }
}