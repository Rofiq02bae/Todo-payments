<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransWebhookService
{
    public function __construct(protected PdfExportService $pdfExportService)
    {
    }

    public function process(array $payload): ?Payment{
        $payment = Payment::where('order_id', $payload['order_id'])->first();

        if(!$payment){
            return null;
        }

        $payment->status = $payload['transaction_status'];
        $payment->transaction_id = $payload['transaction_id'] ?? null;
        $payment->payment_type = $payload['payment_type'] ?? null;
        $payment->save();

        // If payment is successful, generate PDF as fallback
        // (in case frontend didn't trigger it via onSuccess)
        if (in_array($payment->status, ['capture', 'settlement', 'success'])) {
            try {
                $existing = $this->pdfExportService->getLatestForTodo($payment->todo);
                if (!$existing) {
                    $this->pdfExportService->generate($payment->todo, $payment);
                    Log::info('PDF auto-generated via webhook', [
                        'payment_id' => $payment->id,
                        'todo_id'    => $payment->todo_id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to generate PDF via webhook', [
                    'payment_id' => $payment->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        return $payment;
    }
}
