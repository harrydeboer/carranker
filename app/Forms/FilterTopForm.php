<?php

declare(strict_types=1);

namespace App\Forms;

use App\Models\Aspect;
use App\CarSpecs;

class FilterTopForm extends BaseForm
{
    public $hasRequest = false;
    protected $fillable = ['minNumVotes', 'aspects', 'specsChoice', 'specsRange', 'numberOfRows'];

    /** This form has default values for the filtering of the top. */
    public function __construct(array $attributes = [])
    {
        if ($attributes !== []) {
            $this->hasRequest = true;
        }

        $array = [];
        foreach (Aspect::getAspects() as $aspect) {
            $array[$aspect] = 1;
        }
        $this->aspects = $array;
        $array = [];
        foreach (CarSpecs::specsChoice() as $specName => $spec) {
            $array['checkAll' . $specName] = 1;
            foreach ($spec['choices'] as $key => $choice) {
                $array[$specName . $key] = 1;
            }
        }
        $this->specsChoice = $array;
        $array = [];
        foreach (CarSpecs::specsRange() as $specName => $spec) {
            $array[$specName . 'min'] = null;
            $array[$specName . 'max'] = null;
        }
        $this->specsRange = $array;
        $this->minNumVotes = 30;
        $this->numberOfRows = 10;
        parent::__construct($attributes);
    }

    public function rules(): array
    {
        return [
            'minNumVotes' => 'required|integer',
            'aspects.*' => 'required|integer',
            'specsChoice.*' => 'integer|nullable',
            'specsRange.*' => 'numeric|nullable',
            'numberOfRows' => 'numeric|nullable',
        ];
    }
}
