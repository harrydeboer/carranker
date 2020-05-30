<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Aspect;
use App\Models\Model;

class ModelRepository extends BaseRepository
{
    protected $index = 'models';

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

        $model = $this->client->search($params);

        if (is_null($model)) {
            abort(404, "The requested model does not exist.");
        }

        return $model;
    }

    public function addAllToIndex(): void
    {
        $models = Model::all();

        foreach ($models as $model) {
            $params = [
                'index' => $this->index,
                'id'    => $model->getId(),
                'body'  => [
                    'name' => $model->getName(),
                    'content' => $model->getContent(),
                    'make_id' => $model->getMake()->getId(),
                    'make' => $model->getMakename(),
                    'price' => $model->getPrice(1),
                    'votes' => $model->getVotes(),
                    'wiki_car_model' => $model->getWikiCarModel(),
                ],
            ];
            foreach (Aspect::getAspects() as $aspect) {
                $params['body'][$aspect] = $model->getAspect($aspect);
            }
            $this->client->index($params);
        }
    }
}