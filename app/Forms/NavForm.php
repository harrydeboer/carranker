<?php

declare(strict_types=1);

namespace App\Forms;

class NavForm extends BaseForm
{
    public $fillable = ['make', 'model', 'search', 'reCaptchaTokenNavbar'];

    public function rules()
    {
        return [
            'make' => 'string|nullable',
            'model' => 'string|nullable',
            'search' => 'string|nullable',
            'reCaptchaTokenNavbar' => 'string',
        ];
    }
}