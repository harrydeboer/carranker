<?php

declare(strict_types=1);

namespace App\Parameters;

class CarSpecs
{
    /**
     * The car trims have specs of which the possible values are fixed choices. On the homepage these specs can be
     * shown or hidden and the name of the spec has a more readable display name.
     */
    public static function specsChoice(): array
    {
        return [
            'framework' => [
                'show' => true,
                'display' => 'Body Type',
                'choices' => [
                    'Sedan',
                    'Coupé',
                    'Van',
                    'Stationwagon',
                    'Cabriolet',
                    'Roadster',
                    'Crossover',
                    'Pickup',
                    'Hardtop',
                    'Liftback',
                    'Fastback',
                    'Chassis',
                    'Targa',
                    'Hatchback',
                    'Minibus',
                    'Board',
                ],
            ],
            'fuel' => [
                'show' => true,
                'display' => 'Fuel',
                'choices' => [
                    'Gasoline',
                    'Diesel',
                    'CNG',
                    'Hybrid',
                    'Electric',
                ],
            ],
            'number_of_doors' => [
                'show' => true,
                'display' => 'Number of doors',
                'choices' => [
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                ],
            ],
            'number_of_seats' => [
                'show' => false,
                'display' => 'Number of seats',
                'choices' => [
                    2,
                    3,
                    4,
                    5,
                    6,
                ],
            ],
            'number_of_gears' => [
                'show' => false,
                'display' => 'Number of gears',
                'choices' => [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                ],
            ],
            'gearbox_type' => [
                'show' => true,
                'display' => 'Transmission',
                'choices' => [
                    'Manual',
                    'Automatic',
                    'Electronic',
                    'Continuously variable transmission (CVT)',
                ],
            ],
        ];
    }

    /**
     * Certain car trim specs can have a range of values within a minimum and maximum. The homepage has min and max
     * selects where the options are the minimum towards the maximum in certain steps. The specs can be shown or hidden.
     * The name of the spec has a more readable display name. Each spec has a unit, like liters,
     * which is also displayed on the homepage.
     */
    public static function specsRange(): array
    {
        $specsRange = [
            'max_trunk_capacity' => [
                'min' => 0,
                'max' => 3000,
                'step' => 100,
                'display' => 'Trunk capacity',
                'show' => false,
                'unit' => 'liters',
            ],
            'engine_capacity' => [
                'min' => 0.4,
                'max' => 7.0,
                'step' => 0.2,
                'display' => 'Engine capacity',
                'show' => false,
                'unit' => 'liters',
            ],
            'fueltank_capacity' => [
                'min' => 10,
                'max' => 120,
                'step' => 5,
                'display' => 'Fueltank capacity',
                'show' => false,
                'unit' => 'liters',
            ],
            'max_speed' => [
                'min' => 60,
                'max' => 250,
                'step' => 10,
                'display' => 'Maximum speed',
                'show' => false,
                'unit' => 'km/h',
            ],
            'full_weight' => [
                'min' => 600,
                'max' => 4000,
                'step' => 200,
                'display' => 'Weight',
                'show' => true,
                'unit' => 'kg',
            ],
            'engine_power' => [
                'min' => 0,
                'max' => 400,
                'step' => 20,
                'display' => 'Horsepower',
                'show' => true,
                'unit' => 'hp',
            ],
            'acceleration' => [
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'display' => 'Acceleration (0-100km/h)',
                'show' => false,
                'unit' => 'seconds',
            ],
            'fuel_consumption' => [
                'min' => 0,
                'max' => 22,
                'step' => 2,
                'display' => 'Fuel consumption',
                'show' => false,
                'unit' => 'liters/100km',
            ],
            'price' => [
                'min' => 0,
                'max' => 50000,
                'step' => 1000,
                'display' => 'Price',
                'show' => true,
                'unit' => '$',
            ],
            'generation' => [
                'min' => 1970,
                'max' => 2019,
                'step' => 1,
                'display' => 'Year of build',
                'show' => true,
                'unit' => null,
            ],
        ];

        /**
         * The choices for the min and max select are generated. For the spec generation the min and max
         * selects are reversed so that the most recent year is displayed first in the selects.
         */
        foreach ($specsRange as $key => $spec) {
            $specsRange[$key]['minRange'] = self::getRange($key, $spec, 'Min');
            $specsRange[$key]['maxRange'] = self::getRange($key, $spec, 'Max');
        }

        return $specsRange;
    }

    private static function getRange(string $specName, array $spec, string $minOrMax): array
    {
        $range = [];
        $range[$minOrMax] = '';
        if ($specName === 'generation') {
            for ($i = $spec['max']; $i >= $spec['min']; $i = $i - $spec['step']) {
                $range["{$i}"] = $i;
            }
        } else {
            for ($i = $spec['min']; $i <= $spec['max']; $i = $i + $spec['step']) {
                $range["{$i}"] = $i;
            }

            /**
             * The for loop does not always end at the maximum for floats.
             * When the maximum is not present it is added to the choices of the min and max select.
             */
            if ($specName === 'engine_capacity' && !isset($range["7"])) {
                $range["7"] = 7.0;
            }
        }

        return $range;
    }
}
