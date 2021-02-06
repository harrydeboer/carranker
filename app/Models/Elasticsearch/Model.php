<?php

declare(strict_types=1);

namespace App\Models\Elasticsearch;

use App\Models\MySQL\Aspects;
use App\Models\MySQL\ModelTrait;

/**
 * Model stands for car model instead of Laravel model.
 */
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

    public function getTrims(): array
    {
        return $this->hasMany(Trim::class,'model_id');
    }
}
