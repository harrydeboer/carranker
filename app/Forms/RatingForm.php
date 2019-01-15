<?php

declare(strict_types=1);

namespace App\Forms;

class RatingForm extends BaseForm
{
    public $fillable = ['star', 'generation', 'serie', 'trim', 'content', 'reCaptchaToken'];

    public function rules(): array
    {
        return [
            'star.*' => 'integer',
            'generation' => 'string',
            'serie' => 'string',
            'trim' => 'integer',
            'content' => 'string|nullable',
            'reCaptchaToken' => 'string|required',
        ];
    }
}