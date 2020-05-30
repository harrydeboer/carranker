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
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id' => $model->getId(),
                ]
            ];

            $params['body'][] = $this->propertiesToParams($model);
        }

        $this->client->bulk($params);
    }
}
