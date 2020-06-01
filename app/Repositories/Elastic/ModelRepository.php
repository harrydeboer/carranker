<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Elastic\Model;

class ModelRepository extends BaseRepository
{
    protected string $index = 'models';

    public function getModelNames(): array
    {
        $params = [
            'index' => $this->index,
            'size' => 2000,
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];
        $models = $this->modelClassName::search($params);
        $modelnames = [];
        foreach($models as $model) {
            $modelnames[] = $model->getMakename() . ';' . $model->getName();
        }

        return $modelnames;
    }

    public function getByMakeModelName(string $makename, string $modelname): Model
    {
        $params = [
            'index' => $this->index,
            'size' => 2000,
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => [ 'make' => $makename ]],
                            ['match' => [ 'name' => $modelname ]],
                        ]
                    ],
                ],
            ],
        ];
        $model = $this->modelClassName::search($params)[0];

        if (is_null($model)) {
            abort(404, "The requested model does not exist.");
        }

        return $model;
    }
}
