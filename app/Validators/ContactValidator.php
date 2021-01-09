<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ContactValidator extends BaseValidator
{
    public function __construct(
        private Collection $profanities,
    ){}

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'subject' => 'string|required',
            'name' => 'string|required',
            'message' => 'string|required',
            'reCAPTCHAToken' => 'string|required',
        ];
    }

    public function validate(Request $request): array
    {
        $data = parent::validate($request);

        if ($this->profanitiesCheck($data['subject'], $this->profanities) &&
            $this->profanitiesCheck($data['name'], $this->profanities) &&
            $this->profanitiesCheck($data['message'], $this->profanities)) {

            return $data;
        }

        throw ValidationException::withMessages(['profanities' => 'No swearing please.']);
    }
}
