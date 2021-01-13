<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\MakeTrait;
use Illuminate\Database\Eloquent\Collection;

class Make extends BaseModel
{
    use MakeTrait;

    protected string $name;
    protected ?string $wiki_car_make;
    protected ?string $content;

    protected string $image;
    protected static string $index = 'makes';
    protected array $keywords = ['name', 'wiki_car_make'];
    protected array $texts = ['content'];

    public function getModels(): Collection
    {
        return $this->hasMany('\App\Models\Elastic\Model', 'make_id');
    }
}
