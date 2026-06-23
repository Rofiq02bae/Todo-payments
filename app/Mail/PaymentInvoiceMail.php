<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment,
        public Todo $todo
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Berhasil — ' . $this->todo->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment-invoice',
            with: [
                'payment' => $this->payment,
                'todo' => $this->todo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
