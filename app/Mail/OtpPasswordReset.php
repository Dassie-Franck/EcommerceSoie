<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $otp,
        public readonly string $userName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre code de réinitialisation de mot de passe',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.otp-password-reset',
            with: [
                'otp'      => $this->otp,
                'userName' => $this->userName,
                'expiry'   => '10 minutes',
            ]
        );
    }
}
