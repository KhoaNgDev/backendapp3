<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public string $otp;
    public string $context;

    public function __construct(string $otp, string $context)
    {
        $this->otp = $otp;
        $this->context = $context;
    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Otp Mail',
        );
    }

    public function build()
    {
        return $this->subject("Mã OTP xác thực ({$this->context})")
            ->view('mail.otp')
            ->with(['otp' => $this->otp, 'context' => $this->context]);
    }

}
