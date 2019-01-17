<?php

declare(strict_types=1);

namespace App\Forms;

class RatingForm extends BaseForm
{
    public $fillable = ['star', 'generation', 'serie', 'trimId', 'content', 'reCaptchaToken'];

    public function rules(): array
    {
        return [
            'star.*' => 'integer',
            'generation' => 'string',
            'serie' => 'string',
            'trimId' => 'integer',
            'content' => 'string|nullable',
            'reCaptchaToken' => 'string|required',
        ];
    }
}