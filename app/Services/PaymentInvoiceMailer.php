<?php

namespace App\Services;

use App\Mail\PaymentInvoiceMail;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PaymentInvoiceMailer
{
    public function send(Payment $payment): bool
    {
        if ($payment->invoice_sent_at) {
            return true;
        }

        $payment->loadMissing('todo.user');

        $todo = $payment->todo;
        $user = $todo?->user;

        if (! $todo || ! $user?->email) {
            Log::warning('Invoice email skipped because payment has no todo user email.', [
                'payment_id' => $payment->id,
                'todo_id' => $payment->todo_id,
            ]);

            return false;
        }

        try {
            Mail::to($user->email)->send(new PaymentInvoiceMail($payment, $todo));

            $payment->forceFill([
                'invoice_sent_at' => now(),
            ])->save();

            return true;
        } catch (Throwable $exception) {
            Log::error('Failed to send invoice email.', [
                'payment_id' => $payment->id,
                'todo_id' => $payment->todo_id,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
