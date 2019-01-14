<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\ContactForm;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function view()
    {
        $data = [
            'controller' => 'contact',
            'title' => 'Contact',
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'success' => false,
            'form' => new ContactForm(),
            'page' => $this->pageRepository->getByName('contact'),
        ];

        return View::make('contact.index')->with($data);
    }

    public function sendMail(Request $request)
    {
        $form = new ContactForm($request->all());

        if ($form->validate($form->reCaptchaToken, $request)) {

            try {
                Mail::send('contact.message', ['userMessage' => $form->message], function (Message $message) use ($form) {
                    $message->from('postmaster@carranker.com', $form->name);
                    $message->replyTo($form->email, $form->name);
                    $message->subject($form->subject);
                    $message->to(env('MAIL_USERNAME'));
                });

            } catch (\Exception $e) {
                return View::make('contact.mailSend')->with(['success' => false]);
            }
        }

        $data = [
            'success' => true,
        ];

        return View::make('contact.mailSend')->with($data);
    }
}