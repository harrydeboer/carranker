<?php

declare(strict_types=1);

namespace App\Forms;

use App\Models\Aspect;
use App\CarSpecs;

class FilterTopForm extends BaseForm
{
    public $fillable = ['minVotes', 'aspects', 'specsChoice', 'specsRange', 'numberOfRows'];

    public function __construct(array $attributes = [])
    {
        $array = [];
        foreach (Aspect::getAspects() as $aspect) {
            $array[$aspect] = 1;
        }
        $this->aspects = $array;
        $array = [];
        foreach (CarSpecs::specsChoice() as $specname => $spec) {
            $array['checkAll' . $specname] = 1;
            foreach ($spec['choices'] as $key => $choice) {
                $array[$specname . $key] = 1;
            }
        }
        $this->specsChoice = $array;
        $array = [];
        foreach (CarSpecs::specsRange() as $specname => $spec) {
            $array[$specname . 'min'] = null;
            $array[$specname . 'max'] = null;
        }
        $this->specsRange = $array;
        $this->minVotes = 30;
        $this->numberOfRows = 10;
        parent::__construct($attributes);
    }

    public function rules(): array
    {
        return [
            'minVotes' => 'required|integer',
            'aspects.*' => 'required|integer',
            'specsChoice.*' => 'integer|nullable',
            'specsRange.*' => 'numeric|nullable',
            'numberOfRows' => 'numeric|nullable',
        ];
    }
}