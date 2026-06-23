<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(protected MidtransService $midtransService)
    {
    }

    public function create(Todo $todo): JsonResponse
    {
        // If todo already has a successful payment, return existing data
        $existingPaid = Payment::where('todo_id', $todo->id)
            ->whereIn('status', ['capture', 'settlement', 'success'])
            ->latest()
            ->first();

        if ($existingPaid) {
            return response()->json([
                'success' => true,
                'message' => 'Todo already paid',
                'already_paid' => true,
                'data' => [
                    'id' => $existingPaid->id,
                    'todo_id' => $existingPaid->todo_id,
                    'order_id' => $existingPaid->order_id,
                    'amount' => $existingPaid->amount,
                    'status' => $existingPaid->status,
                ]
            ]);
        }

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