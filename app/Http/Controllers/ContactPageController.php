<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\ContactForm;
use App\Repositories\PageRepository;
use App\Repositories\ProfanityRepository;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactPageController extends Controller
{
    public function __construct(private Mailer $mailer,
                                private ProfanityRepository $profanityRepository,
                                private PageRepository $pageRepository){}

    public function view(Request $request): Response
    {
        $contactForm = new ContactForm($this->profanityRepository, $request->all());
        $data = [
            'title' => 'Contact',
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'contactForm' => $contactForm,
            'content' => $this->pageRepository->findByName('contact')?->getContent(),
            'reCaptchaKey' => env('reCaptchaKey'),
        ];

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

                $data['success'] = 'Thank you for your mail!';
            } catch (\Exception $e) {
            	Log::debug($e->getMessage());
                $data['error'] = 'Could not deliver mail. Try again later.';
            }
        }

        return response()->view('contactPage.index', $data, 200);
    }
}
