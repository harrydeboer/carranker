<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;
use App\Models\TrimTrait;

class Trim extends BaseModel
{
    use TrimTrait;
    use Aspect;

    protected float $rating;
    protected static string $index = 'trims';
    public array $keywords = ['name', 'make', 'model', 'framework', 'fuel', 'gearbox_type'];
    public array $integers = ['model_id', 'votes', 'number_of_doors', 'number_of_seats', 'number_of_gears', 'year_begin',
        'year_end', 'fueltank_capacity', 'engine_power', 'max_trunk_capacity', 'max_speed', 'full_weight'];
    public array $doubles = ['price', 'engine_capacity', 'acceleration', 'fuel_consumption'];

    public function __construct(array $attributes = [])
    {
        foreach (Aspect::getAspects() as $aspect) {
            $this->doubles[] =$aspect;
        }

        if (isset($attributes['rating'])) {
            $this->rating = $attributes['rating'];
        }

        $this->fillable = array_merge(self::$aspects, $this->fillable);

        parent::__construct($attributes);
    }

    public function getMappings(): array
    {
        $mappings = parent::getMappings();

        $mappings['properties']['rating'] = ['type' => 'double', "index" => false];

        return $mappings;
    }

    public function getModel(): Model
    {
        return $this->hasOne('\App\Models\Elastic\Model', 'id', 'model_id');
    }
}
