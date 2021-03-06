<?php

declare(strict_types=1);

namespace App\Models\Elasticsearch;

use App\Models\Traits\MakeTrait;

class Make extends AbstractModel
{
    use MakeTrait;

    protected string $name;
    protected ?string $wiki_car_make;
    protected ?string $content;

    protected string $image;
    protected static string $index = 'makes';
    protected array $keywords = ['name', 'wiki_car_make'];
    protected array $texts = ['content'];

    public function getModels(): array
    {
        return $this->hasMany(Model::class, 'make_id');
    }

    public function getWikiCarMake(): string
    {
        return $this->wiki_car_make ?? str_replace(' ', '_', $this->getName());
    }
}
