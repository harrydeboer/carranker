<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;
use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Collection;

class Model extends BaseModel
{
    use ModelTrait;
    use Aspect;

    protected static string $index = 'models';
    public array $keywords = ['name', 'make_name', 'wiki_car_model'];
    public array $texts = ['content'];
    public array $integers = ['make_id', 'votes'];
    public array $doubles = ['price'];

    public function __construct(array $attributes = [])
    {
        foreach (Aspect::getAspects() as $aspect) {
            $this->doubles[] =$aspect;
        }
        $this->fillable = array_merge(self::$aspects, $this->fillable);
        parent::__construct($attributes);
    }

    public function getMake(): Make
    {
        return $this->hasOne('\App\Models\Elastic\Make', 'id', 'make_id');
    }

    public function getTrims(): Collection
    {
        return $this->hasMany('\App\Models\Elastic\Trim','model_id');
    }
}
