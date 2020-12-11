<?php

declare(strict_types=1);

namespace App\Forms;

use Illuminate\Http\Request;
use App\Repositories\ProfanityRepository;

class ContactForm extends BaseForm
{
    protected $fillable = ['email', 'subject', 'name', 'message', 'reCaptchaToken'];

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'subject' => 'string|required',
            'name' => 'string|required',
            'message' => 'string|required',
            'reCaptchaToken' => 'string|required',
        ];
    }

    public function validateFull(Request $request, string $token = null): bool
    {
        $result = parent::validateFull($request, $token);

        $profanityRepository = new ProfanityRepository();

        if ($profanityRepository->validate($this->subject) &&
            $profanityRepository->validate($this->name) &&
            $profanityRepository->validate($this->message)) {

            return $result;
        } else {

            return false;
        }
    }
}
