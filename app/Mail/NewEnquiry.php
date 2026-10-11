<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NewEnquiry extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ContactMessage $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Enquiry: ' . ($this->enquiry->subject ?: 'General Enquiry'),
            replyTo: [
                new Address($this->enquiry->email, $this->enquiry->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-enquiry',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
