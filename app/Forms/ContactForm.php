<?php

declare(strict_types=1);

namespace App\Forms;

class ContactForm extends BaseForm
{
    public $fillable = ['email', 'subject', 'name', 'message', 'reCaptchaToken'];

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'subject' => 'string|required',
            'name' => 'string|required',
            'message' => 'string|required',
            'reCaptchaToken' => 'string|required',
        ];
    }
}