<?php

declare(strict_types=1);

namespace App\Forms;

class ContactForm extends BaseForm
{
    public $fillable = ['email', 'subject', 'name', 'message', 'reCaptchaToken'];

    public function rules()
    {
        return [
            'email' => 'required|email',
            'subject' => 'required',
            'name' => 'required',
            'message' => 'required',
            'reCaptchaToken' => 'string',
        ];
    }
}