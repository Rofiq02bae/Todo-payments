<?php

namespace App\Services;

use App\Models\Payment;

class MidtransWebhookService
{
    public function isValidSignature(array $notification): bool
    {
        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            return false;
        }

        $signature = hash(
            'sha512',
            $notification['order_id'].
            $notification['status_code'].
            $notification['gross_amount'].
            $serverKey
        );

        return hash_equals($signature, $notification['signature_key']);
    }

    public function handle(array $notification): Payment|null
    {
        $payment = Payment::where('order_id', $notification['order_id'])->first();

        if (! $payment) {
            return null;
        }

        $payment->status = $notification['transaction_status'];
        $payment->transaction_id = $notification['transaction_id'] ?? null;
        $payment->payment_type = $notification['payment_type'] ?? null;
        $payment->save();

        return $payment;
    }
}
