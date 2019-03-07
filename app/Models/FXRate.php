<?php

declare(strict_types=1);

namespace App\Models;

class FXRate extends BaseModel
{
    protected $table = 'fxrates';
    public $timestamps = false;

    protected $fillable = ['name', 'value'];

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }
}