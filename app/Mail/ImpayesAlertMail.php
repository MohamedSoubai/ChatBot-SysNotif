<?php

namespace App\Mail;

use App\Models\Clients;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImpayesAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $factures;

    public function __construct($client, $factures)
    {
        $this->client   = $client;
        $this->factures = $factures;
    }

    public function build()
    {
        return $this
            ->subject("Rappel : factures impayées")
            ->markdown('emails.impayes.alert');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Impayes Alert Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.impayes.alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
