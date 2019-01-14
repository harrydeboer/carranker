<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

class MakeForm extends Form
{
    public $textFields = ['name' => '', 'wiki_car_make' => ''];
    public $hasContentField = true;

    public function rules()
    {
        return [
            'name' => 'string|required',
            'wiki_car_make' => 'string|nullable',
            'content' => 'string|nullable',
        ];
    }
}