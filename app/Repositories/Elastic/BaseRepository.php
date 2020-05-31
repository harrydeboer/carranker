<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\BaseModel as EloquentBaseModel;
use App\Models\Elastic\BaseModel;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected $client;
    protected $model;
    protected $modelClassName;
    protected $modelClassNameEloquent;

    /** The child of this base repository has a model. The modelname is stored in the property modelClassName. */
    public function __construct()
    {
        $this->client = Client::getClient();

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

        $result = $this->client->get($params);

        return $this->arrayToModel($result);
    }

    public function get(int $int): BaseModel
    {
        $params = [
            'index' => $this->index,
            'id' => $int,
        ];

        return $this->arrayToModel($this->client->get($params));
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

        $makes = $this->arrayToModels($this->client->search($params));

        if (is_null($makes)) {
            abort(404, "The requested make does not exist.");
        }

        return $makes;
    }

    public function hasMany(string $related, string $foreignKey, int $id): Collection
    {
        $classArray = explode('\\', $related);
        $index = strtolower(end($classArray)) . 's';

        $params = [
            'index' => $index,
            'size' => 1000,
            'body'  => [
                'query' => [
                    'match' => [
                        $foreignKey => $id,
                    ],
                ],
            ],
        ];

        return $this->arrayToModels($this->client->search($params), $related);
    }

    public function hasOne(string $related, string $foreignKey, int $localId): BaseModel
    {
        $classArray = explode('\\', $related);
        $index = strtolower(end($classArray)) . 's';

        $params = [
            'index' => $index,
            'id' => $localId,
        ];

        return $this->arrayToModel($this->client->get($params), $related);
    }

    protected function arrayToModel(array $result, string $related=null): BaseModel
    {
        $className = $related ?? $this->modelClassName;
        if (isset($result['hits']['hits'])) {
            $result = $result['hits']['hits'];
        }
        $fillable = array_merge(['id' => (int) $result['_id']], $result['_source']);

        return new $className($fillable);
    }

    protected function arrayToModels(array $results, string $related=null): Collection
    {
        $models = [];
        $results = $results['hits']['hits'];
        $className = $related ?? $this->modelClassName;
        foreach ($results as $result) {
            $fillable = array_merge(['id' => (int) $result['_id']], $result['_source']);
            $models[] = new $className($fillable);
        }

        return new Collection($models);
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

        return $this->arrayToModels($this->client->search($params));
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
        $this->client->indices()->create($params);
    }

    public function deleteIndex()
    {
        $deleteParams = [
            'index' => $this->index,
        ];
        if ($this->client->indices()->exists($deleteParams)) {
            $this->client->indices()->delete($deleteParams);
        }
    }

    public function getMappings(): array
    {
        return $this->client->indices()->getMapping(['index' => $this->index]);
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
                $this->client->bulk($params);
                unset($params);
            }
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->client->bulk($params);
        }
    }
}