<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Elastic\Model;
use stdClass;

class ModelRepository extends BaseRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function get(int $id): Model
    {
        return Model::get($id);
    }

    public function getModelNames(): array
    {
        $params = [
            'index' => $this->model->getIndex(),
            'size' => 2000,
            'body'  => [
                'query' => [
                    'match_all' => new stdClass(),
                ],
            ],
        ];
        $models = Model::searchMany($params);
        $modelNames = [];
        foreach($models as $model) {
            $modelNames[] = $model->getMakeName() . ';' . $model->getName();
        }

        return $modelNames;
    }

    public function getByMakeModelName(string $makeName, string $modelName): Model
    {
        $params = [
            'index' => $this->model->getIndex(),
            'size' => 2000,
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => [ 'make_name' => $makeName ]],
                            ['match' => [ 'name' => $modelName ]],
                        ]
                    ],
                ],
            ],
        ];

        $model = Model::searchOne($params);

        if (is_null($model)) {
            abort(404, "The requested model does not exist.");
        }

        return $model;
    }
}
