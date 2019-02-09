<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\ContactForm;
use App\Repositories\ProfanityRepository;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends BaseController
{
    private $profanityRepository;
    protected $title = 'Contact';

    public function __construct()
    {
        parent::__construct();
        $this->profanityRepository = new ProfanityRepository();
    }

    public function view(): Response
    {
        $this->decorator();

        $data = [
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'form' => new ContactForm(),
            'page' => $this->pageRepository->getByName('contact'),
            'reCaptchaKey' => env('reCaptchaKey'),
        ];

        return response()->view('contact.index', $data, 200);
    }

    public function sendMail(Request $request): Response
    {
        $form = new ContactForm($request->all());

        if ($form->validateFull($request, $form->reCaptchaToken)) {

            try {
                Mail::send('contact.message', ['userMessage' => $form->message], function (Message $message) use ($form)
                {
                    $message->from(env('MAIL_POSTMASTER_USERNAME'), $form->name);
                    $message->replyTo($form->email, $form->name);
                    $message->subject($form->subject);
                    $message->to(env('MAIL_USERNAME'));
                });

            } catch (\Exception $e) {

                return response()->view('contact.mailSend', ['success' => "0"], 200);
            }
        } else {

            return response()->view('contact.mailSend', ['success' => "0"], 200);
        }

        return response()->view('contact.mailSend', ['success' => "1"], 200);
    }
}