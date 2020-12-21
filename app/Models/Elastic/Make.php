<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\MakeTrait;
use Illuminate\Database\Eloquent\Collection;

class Make extends BaseModel
{
    use MakeTrait;

    protected static string $index = 'makes';
    public array $keywords = ['name', 'wiki_car_make'];
    public array $texts = ['content'];

    public function getModels(): Collection
    {
        return $this->hasMany('\App\Models\Elastic\Model', 'make_id');
    }
}
