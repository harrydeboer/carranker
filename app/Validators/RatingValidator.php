<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Aspect;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Repositories\ProfanityRepository;
use Illuminate\Validation\ValidationException;

class RatingValidator extends BaseValidator
{
    public function __construct(
        private Collection $profanities,
    ){}

    public function rules(): array
    {
        $rules = [
            'trimId' => 'integer|required',
            'content' => 'string|nullable',
            'reCAPTCHAToken' => 'string|required',
        ];

        foreach (Aspect::getAspects() as $aspect) {
            $rules['star.' . $aspect] = 'integer|required';
        }

        return $rules;
    }

    public function validate(Request $request): array
    {
        $data = parent::validate($request);

        if ($this->profanitiesCheck($data['content'], $this->profanities)) {

            return $data;
        }

        throw ValidationException::withMessages(['profanities' => 'No swearing please.']);
    }
}
