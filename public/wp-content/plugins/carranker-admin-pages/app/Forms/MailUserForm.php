<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

class MailUserForm extends Form
{
    public $textFields = ['domain' => '', 'password' => '', 'email' => '', 'forward' => ''];
    public $hasContentField = false;

    public function rules()
    {
        return [
            'domain' => 'string|required',
            'password' => 'string|nullable',
            'email' => 'string|required',
            'forward' => 'string|nullable',
        ];
    }
}