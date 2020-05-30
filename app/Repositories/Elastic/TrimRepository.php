<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Aspect;
use App\Models\Trim;

class TrimRepository extends BaseRepository
{
    protected $index = 'trims';

    public function addAllToIndex(): void
    {
        $trims = Trim::all();

        foreach ($trims as $trim) {
            $params = [
                'index' => $this->index,
                'id'    => $trim->getId(),
                'body'  => [
                    'name' => $trim->getName(),
                    'make_id' => $trim->getModel()->getMake()->getId(),
                    'model_id' => $trim->getModel()->getId(),
                    'make' => $trim->getMakename(),
                    'model' => $trim->getModelname(),
                    'price' => $trim->getPrice(1),
                    'votes' => $trim->getVotes(),
                    'year_begin' => $trim->getYearBegin(),
                    'year_end' => $trim->getYearEnd(),
                    'framework' => $trim->getFramework(),
                    'fuel' => $trim->getFuel(),
                    'gearbox_type' => $trim->getTransmission(),
                    'number_of_doors' => $trim->getNumberOfDoors(),
                    'number_of_seats' => $trim->getNumberOfSeats(),
                    'number_of_gears' => $trim->getNumberOfGears(),
                    'max_trunk_capacity' => $trim->getMaxTrunkCapacity(),
                    'engine_capacity' => $trim->getEngineCapacity(),
                    'fueltank_capacity' => $trim->getFueltankCapacity(),
                    'max_speed' => $trim->getMaxspeed(),
                    'full_weight' => $trim->getFullWeight(),
                    'engine_power' => $trim->getEnginePower(),
                    'acceleration' => $trim->getAcceleration(),
                    'fuel_consumption' => $trim->getFuelConsumption(),
                ],
            ];
            foreach (Aspect::getAspects() as $aspect) {
                $params['body'][$aspect] = $trim->getAspect($aspect);
            }
            $this->client->index($params);
        }
    }
}