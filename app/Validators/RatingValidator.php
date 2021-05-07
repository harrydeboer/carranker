<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Traits\AspectsTrait;
use App\Repositories\Interfaces\ProfanityRepositoryInterface;
use Illuminate\Validation\ValidationException;

class RatingValidator extends AbstractValidator
{
    private ProfanityRepositoryInterface $profanityRepository;
    public const MAX_NUMBER_CHARACTERS_REVIEW = 1000;

    public function __construct(
        array $data,
        array $messages = [],
        array $customAttributes = [],
    ) {
        parent::__construct($data, $messages, $customAttributes);

        $this->profanityRepository = app()->make(ProfanityRepositoryInterface::class);
    }

    public function rules(): array
    {
        $rules = [
            'trim-id' => 'integer|required',
            'content' => 'string|nullable|max:' . self::MAX_NUMBER_CHARACTERS_REVIEW,
            're-captcha-token' => 'string|required',
        ];

        foreach (AspectsTrait::getAspects() as $aspect) {
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

            if ($this->profanitiesCheck($data['content'], $this->profanityRepository->all())) {
                return $data;
            }

            throw ValidationException::withMessages(['profanities' => 'No swearing please.']);
        }

        return $data;
    }
}
