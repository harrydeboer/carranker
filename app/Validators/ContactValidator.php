<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ContactValidator extends AbstractValidator
{
    public function __construct(
        array $data,
        private Collection $profanities,
        array $messages = [],
        array $customAttributes = [],
    ) {
        parent::__construct($data, $messages, $customAttributes);
    }

    public function validate(): array
    {
        $data = parent::validate();

        if (
            $this->profanitiesCheck($data['subject'], $this->profanities)
            && $this->profanitiesCheck($data['name'], $this->profanities)
            && $this->profanitiesCheck($data['message'], $this->profanities)
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
