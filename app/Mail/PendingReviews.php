<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingReviews extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): PendingReviews
    {
        return $this->to(env('MAIL_USERNAME'), env('APP_NAME'))
            ->view('mail.pendingReviews');
    }
}
