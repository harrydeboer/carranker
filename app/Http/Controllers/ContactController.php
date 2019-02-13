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

    public function view(Request $request): Response
    {
        $cacheString = 'contact';
        if ($this->redis->get($cacheString) !== false  && $request->getMethod() === 'GET') {

            return response($this->redis->get($cacheString), 200);
        }

        $form = new ContactForm($request->all());
        $data = [
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'form' => $form,
            'page' => $this->pageRepository->getByName('contact'),
            'reCaptchaKey' => env('reCaptchaKey'),
        ];

        if ($form->validateFull($request, $form->reCaptchaToken)) {
            try {
                Mail::send('contact.message', ['userMessage' => $form->message], function (Message $message) use ($form)
                {
                    $message->from(env('MAIL_POSTMASTER_USERNAME'), $form->name);
                    $message->replyTo($form->email, $form->name);
                    $message->subject($form->subject);
                    $message->to(env('MAIL_USERNAME'));
                });

                $data['success'] = 'Thank you for your mail!';
            } catch (\Exception $e) {
                $data['error'] = 'Could not deliver mail. Try again later.';
            }
        }

        $response = response()->view('contact.index', $data, 200);

        if ($request->getMethod() === 'GET') {
            $this->redis->set($cacheString, $response->getContent(), $this->cacheExpire);
        }

        return $response;
    }
}