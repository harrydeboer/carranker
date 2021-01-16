<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Aspects;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class RatingValidator extends BaseValidator
{
    public const MAX_NUMBER_CHARACTERS_REVIEW = 1000;

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
            'trim-id' => 'integer|required',
            'content' => 'string|nullable|max:' . (string) self::MAX_NUMBER_CHARACTERS_REVIEW,
            're-captcha-token' => 'string|required',
        ];

        foreach (Aspects::getAspects() as $aspect) {
            $rules['star.' . $aspect] = 'integer|required|between:1,10';
        }

        return $rules;
    }

    public function validate(): array
    {
        $data = parent::validate();

        if (!is_null($data['content'])) {

            /** No html in the review. */
            $data['content'] = strip_tags($data['content']);

            if ($this->profanitiesCheck($data['content'], $this->profanities)) {
                return $data;
            }

            throw ValidationException::withMessages(['profanities' => 'No swearing please.']);
        }

        return $data;
    }
}
