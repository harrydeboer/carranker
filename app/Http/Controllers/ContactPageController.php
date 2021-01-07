<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\ProfanityRepository;
use App\Validators\ContactValidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;

class ContactPageController extends Controller
{
    public function __construct(
        private Mailer $mailer,
        private ProfanityRepository $profanityRepository,
        private PageRepository $pageRepository,
        private LogManager $logManager,
    )
    {

    }

    public function view(): Response
    {
        $data = [
            'title' => 'Contact',
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'content' => $this->pageRepository->findByName('contact')?->getContent(),
            'reCaptchaKey' => env('reCaptchaKey'),
        ];

        return response()->view('contactPage.index', $data);
    }

    public function sendMail(Request $request): RedirectResponse
    {
        $contactForm = new ContactValidator($this->profanityRepository, $request->all());
        if ($contactForm->validateFull($request, $contactForm->reCaptchaToken)) {
            try {
                $this->mailer->send('contactPage.message', ['userMessage' => $contactForm->message],
                    function (Message $message) use ($contactForm)
                {
                    $message->from(env('MAIL_POSTMASTER_USERNAME'), $contactForm->name);
                    $message->replyTo($contactForm->email, $contactForm->name);
                    $message->subject($contactForm->subject);
                    $message->to(env('MAIL_USERNAME'));
                });

                return redirect(route('contactPage'))->with('success', 'Thank you for your mail!');
            } catch (\Exception $e) {
            	$this->logManager->debug($e->getMessage());

                return redirect(route('contactPage'))->withErrors('Could not deliver mail. Try again later.');
            }
        }

        return redirect(route('contactPage'))->withErrors('The posted data was invalid.');
    }
}
