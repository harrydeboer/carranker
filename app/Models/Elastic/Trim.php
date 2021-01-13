<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspects;
use App\Models\TrimTrait;

class Trim extends BaseModel
{
    use TrimTrait;
    use Aspects;
    use AspectsProperties;

    protected ?string $name;
    protected string $make_name;
    protected string $model_name;
    protected int $model_id;
    protected ?float $price;
    protected int $votes;
    protected ?string $framework;
    protected ?string $fuel;
    protected ?string $gearbox_type;
    protected ?int $number_of_doors;
    protected ?int $number_of_seats;
    protected ?int $number_of_gears;
    protected int $year_begin;
    protected int $year_end;
    protected ?int $fueltank_capacity;
    protected ?int $engine_power;
    protected ?int $max_trunk_capacity;
    protected ?int $max_speed;
    protected ?int $full_weight;
    protected ?float $engine_capacity;
    protected ?float $acceleration;
    protected ?float $fuel_consumption;

    protected float $rating;
    protected static string $index = 'trims';
    protected array $keywords = ['name', 'make_name', 'model_name', 'framework', 'fuel', 'gearbox_type'];
    protected array $integers = ['model_id', 'votes', 'number_of_doors', 'number_of_seats', 'number_of_gears', 'year_begin',
        'year_end', 'fueltank_capacity', 'engine_power', 'max_trunk_capacity', 'max_speed', 'full_weight'];
    protected array $doubles = ['price', 'engine_capacity', 'acceleration', 'fuel_consumption'];

    public function getMappings(): array
    {
        $mappings = parent::getMappings();

        $mappings['properties']['rating'] = ['type' => 'double', "index" => false];

        return $mappings;
    }

    /**
     * @return Model
     */
    public function getModel(): BaseModel
    {
        return $this->hasOne('\App\Models\Elastic\Model', 'model_id');
    }
}
