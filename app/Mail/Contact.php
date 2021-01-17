<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        private array $formData,
    ) {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): Contact
    {
        return $this->to(env('MAIL_USERNAME'), env('APP_NAME'))
            ->from(env('MAIL_POSTMASTER_USERNAME'), $this->formData['name'])
            ->replyTo($this->formData['email'], $this->formData['name'])
            ->view('mail.contact', [
                'userMessage' => $this->formData['message'],
            ]);
    }
}
