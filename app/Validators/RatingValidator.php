<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Aspect;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class RatingValidator extends BaseValidator
{
    public const maxNumberCharactersReview = 1000;

    public function __construct(
        array $data,
        private Collection $profanities,
        array $messages = [],
        array $customAttributes = [],
    )
    {
        parent::__construct($data, $messages, $customAttributes);
    }

    public function rules(): array
    {
        $rules = [
            'trimId' => 'integer|required',
            'content' => 'string|nullable|max:' . (string) self::maxNumberCharactersReview,
            'reCAPTCHAToken' => 'string|required',
        ];

        foreach (Aspect::getAspects() as $aspect) {
            $rules['star.' . $aspect] = 'integer|required';
        }

        return $rules;
    }

    public function validate(): array
    {
        $data = parent::validate();

        if ($this->profanitiesCheck($data['content'], $this->profanities)) {

            return $data;
        }

        throw ValidationException::withMessages(['profanities' => 'No swearing please.']);
    }
}
