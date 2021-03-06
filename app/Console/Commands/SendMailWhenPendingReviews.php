<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\PendingReviews;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Mail\Mailer;

/** @noinspection PhpUnused */
class SendMailWhenPendingReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-mail:when-pending-reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail when there are pending reviews';

    public function __construct(
        private Mailer $mailer,
        private RatingRepositoryInterface $ratingRepository,
    ) {
        parent::__construct();
    }


    public function handle()
    {
        $pendingReviews = $this->ratingRepository->findPendingReviews(1);

        if (count($pendingReviews) > 0) {
            $this->mailer->send(new PendingReviews());
        }

        $this->info('Sent mail when there were pending reviews.');
    }
}
