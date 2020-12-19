<?php

declare(strict_types=1);

namespace App\Forms;

use App\Models\Aspect;
use Illuminate\Http\Request;
use App\Repositories\ProfanityRepository;

class RatingForm extends BaseForm
{
    protected $fillable = ['star', 'generation', 'serie', 'trimId', 'content', 'reCaptchaToken'];

    public function __construct(private ProfanityRepository $profanityRepository, array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function rules(): array
    {
        $rules = [
            'generation' => 'string|required',
            'serie' => 'string|required',
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
