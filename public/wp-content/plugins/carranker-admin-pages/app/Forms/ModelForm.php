<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

use CarrankerAdmin\App\Models\Make;

class ModelForm extends Form
{
    public $textFields = ['name' => '', 'wiki_car_model' => ''];
    public $hasContentField = true;
    public $integerFields = ['votes' => 0];
    public $floatFields = ['price' => 0, 'design' => 0, 'costs' => 0, 'comfort' => 0, 'reliability' => 0, 'performance' => 0];
    public $hiddenFields = ['id' => 0, 'carrankerAdminAction' => 'create'];
    public $selectFields = ['make' => ''];
    public $selectChoices = ['make' => []];

    public function __construct(string $createOrUpdate, object $request = null)
    {
        parent::__construct($createOrUpdate, $request);
        $this->selectChoices['make'] = Make::getMakenames();
    }

    public function rules()
    {
        return [
            'name' => 'string|required',
            'wiki_car_model' => 'string|nullable',
            'make' => 'string|required',
            'content' => 'string|nullable',
            'votes' => 'integer|required',
            'price' => 'float|required',
            'design' => 'float|required',
            'costs' => 'float|required',
            'comfort' => 'float|required',
            'reliability' => 'float|required',
            'performance' => 'float|required',
        ];
    }

    public function validate(object $request)
    {
        parent::validate($request);

        if (!in_array($request->make, Make::getMakenames())) {
            $this->errors['make'] = 'The make does not exist.';
            return false;
        }

        return true;
    }
}