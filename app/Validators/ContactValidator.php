<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Http\Request;
use App\Repositories\ProfanityRepository;

class ContactValidator extends BaseValidator
{
    protected $fillable = ['email', 'subject', 'name', 'message', 'reCaptchaToken'];

    public function __construct(private ProfanityRepository $profanityRepository, array $attributes = [])
    {
        parent::__construct($attributes);
    }

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

        if ($this->profanityRepository->validate($this->subject) &&
            $this->profanityRepository->validate($this->name) &&
            $this->profanityRepository->validate($this->message)) {

            return $result;
        } else {

            return false;
        }
    }
}
