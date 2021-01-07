<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Aspect;
use Illuminate\Http\Request;
use App\Repositories\ProfanityRepository;

class RatingValidator extends BaseValidator
{
    protected $fillable = ['star', 'generation', 'series', 'trimId', 'content', 'reCaptchaToken'];

    public function __construct(private ProfanityRepository $profanityRepository, array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function rules(): array
    {
        $rules = [
            'generation' => 'string|required',
            'series' => 'string|required',
            'trimId' => 'string|required',
            'content' => 'string|nullable',
            'reCaptchaToken' => 'string|required',
        ];

        foreach (Aspect::getAspects() as $aspect) {
            $rules['star.' . $aspect] = 'integer|required';
        }

        return $rules;
    }

    public function validateFull(Request $request, string $token = null): bool
    {
        $result = parent::validateFull($request, $token);

        if ($this->profanityRepository->validate($this->content)) {

            return $result;
        } else {

            return false;
        }
    }
}
