<?php

declare(strict_types=1);

namespace App;

class CarSpecs
{
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

    public static function specsRange(): array
    {
        $specsRange = [
            'max_trunk_capacity' => [
                'min' => 0.0,
                'max' => 3000.0,
                'step' => 100.0,
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
                'min' => 10.0,
                'max' => 120.0,
                'step' => 5.0,
                'display' => 'Fueltank capacity',
                'show' => false,
                'unit' => 'liters',
            ],
            'max_speed' => [
                'min' => 60.0,
                'max' => 250.0,
                'step' => 10.0,
                'display' => 'Maximum speed',
                'show' => false,
                'unit' => 'km/h',
            ],
            'full_weight' => [
                'min' => 600.0,
                'max' => 4000.0,
                'step' => 200.0,
                'display' => 'Weight',
                'show' => true,
                'unit' => 'kg',
            ],
            'engine_power' => [
                'min' => 0.0,
                'max' => 400.0,
                'step' => 20.0,
                'display' => 'Horsepower',
                'show' => true,
                'unit' => 'hp',
            ],
            'acceleration' => [
                'min' => 0.0,
                'max' => 20.0,
                'step' => 1.0,
                'display' => 'Acceleration (0-100km/h)',
                'show' => false,
                'unit' => 'seconds',
            ],
            'fuel_consumption' => [
                'min' => 0.0,
                'max' => 22.0,
                'step' => 2.0,
                'display' => 'Fuel consumption',
                'show' => false,
                'unit' => 'liters/100km',
            ],
            'price' => [
                'min' => 0.0,
                'max' => 50000.0,
                'step' => 1000.0,
                'display' => 'Price',
                'show' => true,
                'unit' => '$',
            ],
            'generation' => [
                'min' => 1970.0,
                'max' => 2019.0,
                'step' => 1.0,
                'display' => 'Year of build',
                'show' => true,
                'unit' => null,
            ],
        ];

        foreach ($specsRange as $key => $spec) {
            $specsRange[$key]['minRange'] = self::getRange($key, $spec, 'Min');
            $specsRange[$key]['maxRange'] = self::getRange($key, $spec, 'Max');
        }

        return $specsRange;
    }

    private static function getRange(string $specname, array $spec, string $minOrMax): array
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