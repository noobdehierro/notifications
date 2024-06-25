<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public $placeholder;

    public $campaignName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $placeholder, $campaignName)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->campaignName = $campaignName;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Notification',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'name' => $this->name,
                'placeholder' => $this->placeholder,
                'campaignName' => $this->campaignName
            ],
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
