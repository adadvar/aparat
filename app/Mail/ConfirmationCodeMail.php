<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    private $code;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $code, string $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
            ->view('emails.confirmation-code')
            ->with('code', $this->code);
    }
}
