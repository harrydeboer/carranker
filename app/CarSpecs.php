<?php

declare(strict_types=1);

namespace App;

class CarSpecs
{
    public static function specsChoice(): array
    {
        return [
            'framework' => [
                'show' => 1,
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
                'show' => 1,
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
                'show' => 1,
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
                'show' => 0,
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
                'show' => 0,
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
                'show' => 1,
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

    public static function specsRange(): array
    {
        $specsRange = [
            'max_trunk_capacity' => [
                'min' => 0,
                'max' => 3000,
                'step' => 100,
                'display' => 'Trunk capacity',
                'show' => 0,
                'unit' => 'liters',
            ],
            'engine_capacity' => [
                'min' => 0.4,
                'max' => 7,
                'step' => 0.2,
                'display' => 'Engine capacity',
                'show' => 0,
                'unit' => 'liters',
            ],
            'fueltank_capacity' => [
                'min' => 10,
                'max' => 120,
                'step' => 5,
                'display' => 'Fueltank capacity',
                'show' => 0,
                'unit' => 'liters',
            ],
            'max_speed' => [
                'min' => 60,
                'max' => 250,
                'step' => 10,
                'display' => 'Maximum speed',
                'show' => 0,
                'unit' => 'km/h',
            ],
            'full_weight' => [
                'min' => 600,
                'max' => 4000,
                'step' => 200,
                'display' => 'Weight',
                'show' => 1,
                'unit' => 'kg',
            ],
            'engine_power' => [
                'min' => 0,
                'max' => 400,
                'step' => 20,
                'display' => 'Horsepower',
                'show' => 1,
                'unit' => 'hp',
            ],
            'acceleration' => [
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'display' => 'Acceleration (0-100km/h)',
                'show' => 0,
                'unit' => 'seconds',
            ],
            'fuel_consumption' => [
                'min' => 0,
                'max' => 22,
                'step' => 2,
                'display' => 'Fuel consumption',
                'show' => 0,
                'unit' => 'liters/100km',
            ],
            'price' => [
                'min' => 0,
                'max' => 50000,
                'step' => 1000,
                'display' => 'Price',
                'show' => 1,
                'unit' => '$',
            ],
            'generation' => [
                'min' => 1970,
                'max' => 2019,
                'step' => 1,
                'display' => 'Year of build',
                'show' => 1,
                'unit' => null,
            ],
        ];

        foreach ($specsRange as $key => $spec) {
            $specsRange[$key]['minRange'] = self::getRange($key, $spec, 'Min');
            $specsRange[$key]['maxRange'] = self::getRange($key, $spec, 'Max');
        }

        return $specsRange;
    }

    private static function getRange(string $specname, array $spec, string $minOrMax)
    {
        $range = [];
        if ($specname === 'generation') {
            $range[''] = $minOrMax;
            for ($i = $spec['max']; $i >= (int)$spec['min']; $i = $i - $spec['step']) {
                $range["{$i}"] = $i;
            }
        } else {
            $range[''] = $minOrMax;
            for ($i = $spec['min']; $i <= (int)$spec['max']; $i = $i + $spec['step']) {
                $range["{$i}"] = $i;
            }
        }

        return $range;
    }
}