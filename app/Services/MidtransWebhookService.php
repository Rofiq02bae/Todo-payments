<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransWebhookService
{
    public function __construct(
        protected PdfExportService $pdfExportService,
        protected PaymentInvoiceMailer $paymentInvoiceMailer,
    ) {}

    public function process(array $payload): ?Payment
    {
        if (! $this->verifySignature($payload)) {
            Log::warning('Midtrans webhook: invalid signature', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return null;
        }

        $payment = Payment::where('order_id', $payload['order_id'])->first();

        if (! $payment) {
            return null;
        }

        $payment->status = $payload['transaction_status'];
        $payment->transaction_id = $payload['transaction_id'] ?? null;
        $payment->payment_type = $payload['payment_type'] ?? null;
        $payment->save();

        if (in_array($payment->status, ['capture', 'settlement', 'success'])) {
            try {
                $existing = $this->pdfExportService->getLatestForTodo($payment->todo);
                if (! $existing) {
                    $this->pdfExportService->generate($payment->todo, $payment);
                    Log::info('PDF auto-generated via webhook', [
                        'payment_id' => $payment->id,
                        'todo_id' => $payment->todo_id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to generate PDF via webhook', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $this->paymentInvoiceMailer->send($payment);
        }

        return $payment;
    }

    private function verifySignature(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');
        $receivedSignature = $payload['signature_key'] ?? '';

        $computed = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($computed, $receivedSignature);
    }
}
