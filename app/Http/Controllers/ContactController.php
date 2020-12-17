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

class ContactController extends Controller
{
    private $mailer;
    private $profanityRepository;
    private $pageRepository;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->profanityRepository = new ProfanityRepository();
        $this->pageRepository = new PageRepository();
    }

    public function view(Request $request): Response
    {
        $form = new ContactForm($request->all());
        $data = [
            'title' => 'Contact',
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'form' => $form,
            'page' => $this->pageRepository->getByName('contact'),
            'reCaptchaKey' => env('reCaptchaKey'),
        ];

        if ($form->validateFull($request, $form->reCaptchaToken)) {
            try {
                $this->mailer->send('contact.message', ['userMessage' => $form->message], function (Message $message) use ($form)
                {
                    $message->from(env('MAIL_POSTMASTER_USERNAME'), $form->name);
                    $message->replyTo($form->email, $form->name);
                    $message->subject($form->subject);
                    $message->to(env('MAIL_USERNAME'));
                });

                $data['success'] = 'Thank you for your mail!';
            } catch (\Exception $e) {
            	Log::debug($e->getMessage());
                $data['error'] = 'Could not deliver mail. Try again later.';
            }
        }

        return response()->view('contact.index', $data, 200);
    }
}
