<?php

declare(strict_types=1);

namespace App\Forms;

use App\Models\Aspect;

class RatingForm extends BaseForm
{
    public $fillable = ['star', 'generation', 'serie', 'trimId', 'content', 'reCaptchaToken'];

    public function rules(): array
    {
        $rules = [
            'generation' => 'string|required',
            'serie' => 'string|required',
            'trimId' => 'integer|required',
            'content' => 'string|nullable',
            'reCaptchaToken' => 'string|required',
        ];

        foreach (Aspect::getAspects() as $aspect) {
            $rules['star.' . $aspect] = 'integer|required';
        }

        return $rules;
    }
}