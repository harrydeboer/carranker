<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspects;
use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Collection;

class Model extends BaseModel
{
    use ModelTrait;
    use Aspects;
    use AspectsProperties;

    protected string $name;
    protected string $make_name;
    protected int $make_id;
    protected ?string $wiki_car_model;
    protected ?string $content;
    protected ?float $price;
    protected int $votes;

    protected static string $index = 'models';
    protected array $keywords = ['name', 'make_name', 'wiki_car_model'];
    protected array $texts = ['content'];
    protected array $integers = ['make_id', 'votes'];
    protected array $doubles = ['price'];

    /**
     * @return Make
     */
    public function getMake(): BaseModel
    {
        return $this->hasOne(Make::class, 'make_id');
    }

    public function getTrims(): Collection
    {
        return $this->hasMany(Trim::class,'model_id');
    }
}
