<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

class MailUserFormPasswordUpdate extends Form
{
    public $textFields = ['password' => ''];
    public $hasContentField = false;

    public function rules()
    {
        return [
            'password' => 'string|required',
            'id' => 'integer|required',
        ];
    }
}