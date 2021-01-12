<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\ProfanityRepository;
use App\Validators\ContactValidator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Validation\ValidationException;
use Swift_SwiftException;
use Closure;

class ContactPageController extends Controller
{
    public function __construct(
        private Mailer $mailer,
        private ProfanityRepository $profanityRepository,
        private PageRepository $pageRepository,
        private LogManager $logManager,
    ){}

    public function view(): Response
    {
        $data = [
            'title' => 'Contact',
            'profanities' => $this->profanityRepository->getProfanityNames(),
            'content' => $this->pageRepository->findByName('contact')?->getContent(),
            'reCAPTCHAKey' => env('RE_CAPTCHA_KEY'),
        ];

        return response()->view('contactPage.index', $data);
    }

    private function redirectTo(): RedirectResponse
    {
        return redirect(route('contactPage'));
    }

    /**
     * @throws ValidationException
     * @throws BindingResolutionException
     */
    public function sendMail(Request $request): RedirectResponse
    {
        $validator = new ContactValidator($request->all(), $this->profanityRepository->all());
        $data = $validator->validate();

        try {
            $this->mailer->send('contactPage.message',
                                ['userMessage' => $data['message']],
                                $this->mailerCallback($data));

        } catch (Swift_SwiftException $e) {
            $this->logManager->debug($e->getMessage());

            return $this->redirectTo()->withErrors('Could not deliver mail. Try again later.');
        }

        return $this->redirectTo()->with('success', 'Thank you for your mail!');
    }

    /**
     * @throws Swift_SwiftException
     */
    private function mailerCallback($data): Closure
    {
        return function(Message $message) use ($data) {
            $message->from(env('MAIL_POSTMASTER_USERNAME'), $data['name']);
            $message->replyTo($data['email'], $data['name']);
            $message->subject($data['subject']);
            $message->to(env('MAIL_USERNAME'));
        };
    }
}
