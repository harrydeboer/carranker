<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Aspect;
use App\CarSpecs;

class FilterTopValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'minNumVotes' => 'required|integer',
            'aspects.*' => 'required|integer',
            'specsChoice.*' => 'accepted|nullable',
            'specsRange.*' => 'numeric|nullable',
            'numberOfRows' => 'numeric|nullable',
            'offset' => 'numeric|nullable'
        ];
    }
}
