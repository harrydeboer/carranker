<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

class MailUserFormUpdate extends Form
{
    public $textFields = ['domain' => '', 'email' => '', 'forward' => ''];
    public $hasContentField = false;

    public function rules()
    {
        return [
            'domain' => 'string|required',
            'email' => 'string|required',
            'forward' => 'string|nullable',
        ];
    }
}