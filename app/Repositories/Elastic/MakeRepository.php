<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Make;

class MakeRepository extends BaseRepository
{
    protected $index = 'makes';

    public function getByName(string $name)
    {
        $params = [
            'index' => $this->index,
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => $name
                    ]
                ]
            ]
        ];

        $make = $this->client->search($params);

        if (is_null($make)) {
            abort(404, "The requested make does not exist.");
        }

        return $make;
    }

    public function addAllToIndex(): void
    {
        $makes = Make::all();

        foreach ($makes as $make) {
            $params = [
                'index' => $this->index,
                'id'    => $make->getId(),
                'body'  => [
                    'name' => $make->getName(),
                    'content' => $make->getContent(),
                    'wiki_car_make' => $make->getWikiCarMake(),
                ],
            ];
            $this->client->index($params);
        }
    }
}