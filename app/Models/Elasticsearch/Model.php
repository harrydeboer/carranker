<?php

declare(strict_types=1);

namespace App\Models\Elasticsearch;

use App\Models\Traits\AspectsTrait;
use App\Models\Traits\ModelTrait;

/**
 * Model stands for car model instead of Laravel model.
 */
class Model extends AbstractModel
{
    use ModelTrait;
    use AspectsTrait;
    use AspectsPropertiesTrait;

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
    public function getMake(): AbstractModel
    {
        return $this->hasOne(Make::class, 'make_id');
    }

    public function getTrims(): array
    {
        return $this->hasMany(Trim::class,'model_id');
    }

    public function getWikiCarModel(): string
    {
        if (empty($this->wiki_car_model)) {
            return str_replace(' ', '_', $this->getMakeName() . '_' . $this->getName());
        }

        return str_replace(' ', '_', $this->getMakeName()) . '_' . $this->wiki_car_model;
    }
}
