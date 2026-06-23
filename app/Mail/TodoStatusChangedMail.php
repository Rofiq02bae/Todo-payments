<?php

namespace App\Mail;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TodoStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Todo $todo,
        public string $newStatus
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Todo Diperbarui — ' . $this->todo->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.todo-status-changed',
            with: [
                'todo' => $this->todo,
                'newStatus' => $this->newStatus,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
