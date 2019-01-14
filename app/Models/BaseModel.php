<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function getId(): int
    {
        return $this->id;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if ($attributes !== []) {
            foreach ($attributes as $key => $attribute) {
                if (!in_array($key, $this->fillable)) {
                    throw new \Exception("Attribute assigned that is not fillable.");
                }
            }
        }
    }

    public function testAttributesMatchFillable(): bool
    {
        foreach ($this->attributes as $key => $attribute) {
            if ($key !== 'id' && !in_array($key, $this->fillable)) {
                return false;
            }
        }

        return true;
    }
}