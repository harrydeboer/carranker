<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\BaseModel as EloquentBaseModel;
use App\Models\Elastic\BaseModel;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected $model;
    protected $modelClassName;
    protected $modelClassNameEloquent;

    /** The child of this base repository has a model. The modelname is stored in the property modelClassName. */
    public function __construct()
    {
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = 'App\Models\Elastic\\' . str_replace('Repository', '', end($classNameArray));
        $this->modelClassNameEloquent = 'App\Models\\' . str_replace('Repository', '', end($classNameArray));
        $this->model = new $this->modelClassName();

        if (env('APP_ENV') === 'acceptance') {
            $this->index = 'accept' . $this->index;
        } elseif (env('APP_ENV') === 'testing') {
            $this->index = 'test' . $this->index;
        }
    }

    public function find(int $id): ?BaseModel
    {
        if ($id === 0) {
            return null;
        }

        $params = [
            'index' => $this->index,
            'id' => $id,
        ];

        $result = $this->modelClassName::get($params);

        return $result;
    }

    public function get(int $int): BaseModel
    {
        $params = [
            'index' => $this->index,
            'id' => $int,
        ];

        return $this->modelClassName::get($params);
    }

    public function getByName(string $name): Collection
    {
        $params = [
            'index' => $this->index,
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => $name,
                    ],
                ],
            ],
        ];

        $makes = $this->modelClassName::search($params);

        if (is_null($makes)) {
            abort(404, "The requested make does not exist.");
        }

        return $makes;
    }

    public function index(array $params)
    {
        return $this->modelClassName::index($params);
    }

    public function findForSearch(string $searchString): Collection
    {
        $words = explode(' ', $searchString);

        $params = [
            'index' => $this->index,
            'size' => 100,
            'sort' => ['name:asc'],
        ];

        foreach ($words as $word) {
            $params['body']['query']['bool']['should'][] = ['wildcard' => ['name' => '*' . $word . '*']];
        }

        return $this->modelClassName::search($params);
    }

    public function createIndex()
    {
        $params = [
            'index' => $this->index,
            'body' => [
                'mappings' => $this->model->getMappings(),
            ],
        ];

        // Create the index with mappings and settings now
        $this->modelClassName::indicesCreate($params);
    }

    public function deleteIndex()
    {
        $deleteParams = [
            'index' => $this->index,
        ];
        if ($this->modelClassName::indicesExists($deleteParams)) {
            $this->modelClassName::indicesDelete($deleteParams);
        }
    }

    public function getMappings(): array
    {
        return $this->modelClassName::indicesGetMapping(['index' => $this->index]);
    }

    protected function propertiesToParams(EloquentBaseModel $model)
    {
        $params = [];

        foreach ($this->model->keywords as $keyword) {
            $params[$keyword] = $model->$keyword;
        }

        foreach ($this->model->texts as $text) {
            $params[$text] = $model->$text;
        }

        foreach ($this->model->integers as $integer) {
            $params[$integer] = $model->$integer;
        }

        foreach ($this->model->doubles as $double) {
            $params[$double] = $model->$double;
        }

        foreach ($this->model->timestamps as $timestamp) {
            $params[$timestamp] = $model->$timestamp;
        }

        foreach ($this->model->booleans as $boolean) {
            $params[$boolean] = $model->$boolean;
        }

        return $params;
    }

    public function addAllToIndex(): void
    {
        $models = $this->modelClassNameEloquent::all();

        foreach ($models as $key => $model) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id' => $model->getId(),
                ]
            ];

            $params['body'][] = $this->propertiesToParams($model);

            if ($key % 1000 === 0) {
                $this->modelClassName::bulk($params);
                unset($params);
            }
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->modelClassName::bulk($params);
        }
    }
}