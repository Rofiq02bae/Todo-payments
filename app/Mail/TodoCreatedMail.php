<?php

namespace App\Mail;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TodoCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Todo $todo
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Todo Berhasil Dibuat 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.todo-created',
            with: [
                'todo' => $this->todo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}