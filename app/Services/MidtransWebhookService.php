<?php

namespace App\Services;

use App\Models\Payment;

class MidtransWebhookService
{
    public function handle(array $notification): Payment|null
    {
        $payment = Payment::where('order_id', $notification['order_id'])->first();

        if(!$payment) {
            return null;
        }

        $payment->status = $notification['transaction_status'];

        $payment->transaction_id = $notification['transaction_id'] ?? null;

        $payment->payment_type = $notification['payment_type'] ?? null;

        $payment->save();

        return $payment;
    }
}