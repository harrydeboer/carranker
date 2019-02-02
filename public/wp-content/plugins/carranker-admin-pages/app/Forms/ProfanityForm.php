<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

class ProfanityForm extends Form
{
    public $textFields = ['name' => ''];

    public function rules()
    {
        return [
            'name' => 'string|required',
        ];
    }
}