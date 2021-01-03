<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FXRate extends BaseModel
{
    use HasFactory;

    protected $table = 'fx_rates';
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
