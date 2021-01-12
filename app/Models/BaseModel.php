<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/** All models extend this base model. The base model has two checks.
 * Check if all attributes passed to the constructor are present in the fillable property of the models.
 * For testing: check if the model factory has only set attributes that are present in the fillable property.
 * @mixin Builder
 */
abstract class BaseModel extends Model
{
    public function getId(): int
    {
        return $this->id;
    }

    public function findId(): ?int
    {
        return $this->id;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if ($attributes !== []) {
            foreach ($attributes as $key => $attribute) {
                if (!in_array($key, $this->getFillable())) {
                    throw new \Exception("Attribute assigned that is not fillable.");
                }
            }
        }
    }

    public function testAttributesMatchFillable(): bool
    {
        foreach ($this->getAttributes() as $key => $attribute) {
            if ($key !== 'id' && !in_array($key, $this->fillable)) {
                return false;
            }
        }

        return true;
    }
}