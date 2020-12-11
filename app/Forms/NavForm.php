<?php

declare(strict_types=1);

namespace App\Forms;

class NavForm extends BaseForm
{
    protected $fillable = ['query'];

    public function rules(): array
    {
        return [
            'query' => 'string|required',
        ];
    }
}
