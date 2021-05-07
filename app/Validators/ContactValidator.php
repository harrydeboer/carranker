<?php

declare(strict_types=1);

namespace App\Validators;

use App\Repositories\Interfaces\ProfanityRepositoryInterface;
use Illuminate\Validation\ValidationException;

class ContactValidator extends AbstractValidator
{
    private ProfanityRepositoryInterface $profanityRepository;

    public function __construct(
        array $data,
        array $messages = [],
        array $customAttributes = [],
    ) {
        parent::__construct($data, $messages, $customAttributes);

        $this->profanityRepository = app()->make(ProfanityRepositoryInterface::class);
    }

    public function validate(): array
    {
        $data = parent::validate();

        $profanities = $this->profanityRepository->all();

        if (
            $this->profanitiesCheck($data['subject'], $profanities)
            && $this->profanitiesCheck($data['name'], $profanities)
            && $this->profanitiesCheck($data['message'], $profanities)
        ) {

            return $data;
        }

        throw ValidationException::withMessages(['profanities' => 'No swearing please.']);
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'subject' => 'string|required',
            'name' => 'string|required',
            'message' => 'string|required',
            're-captcha-token' => 'string|required',
        ];
    }
}
