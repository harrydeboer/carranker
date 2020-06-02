<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\MakeTrait;
use Illuminate\Database\Eloquent\Collection;

class Make extends BaseModel
{
    use MakeTrait;

    public $keywords = ['name', 'wiki_car_make'];
    public $texts = ['content'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (isset($attributes['id'])) {
            $this->setAttribute('id', $attributes['id']);
        }
    }

    public function getModels(): Collection
    {
        return $this->hasMany('\App\Models\Elastic\Model', 'make_id');
    }
}