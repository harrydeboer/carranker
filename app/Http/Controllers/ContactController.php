<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\ContactForm;
use App\Repositories\ProfanityRepository;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    private $profanityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->profanityRepository = new ProfanityRepository();
    }

    public function view(): \Illuminate\View\View
    {
        $data = [
            'controller' => 'contact',
            'title' => 'Contact',
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'form' => new ContactForm(),
            'page' => $this->pageRepository->getByName('contact'),
            'reCaptchaKey' => env('reCaptchaKey'),
        ];

        return View::make('contact.index')->with($data);
    }

    public function sendMail(Request $request): \Illuminate\View\View
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

                return View::make('contact.mailSend')->with(['success' => "0"]);
            }
        } else {

            return View::make('contact.mailSend')->with(['success' => "0"]);
        }

        return View::make('contact.mailSend')->with(['success' => "1"]);
    }
}