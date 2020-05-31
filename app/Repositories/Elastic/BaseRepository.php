<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\BaseModel;

abstract class BaseRepository
{
    protected $client;
    protected $model;
    protected $modelClassNameEloquent;

    /** The child of this base repository has a model. The modelname is stored in the property modelClassName. */
    public function __construct()
    {
        $this->client = Client::getClient();

        $classNameArray = explode('\\', static::class);
        $modelClassName = 'App\Models\Elastic\\' . str_replace('Repository', '', end($classNameArray));
        $this->modelClassNameEloquent = 'App\Models\\' . str_replace('Repository', '', end($classNameArray));
        $this->model = new $modelClassName();

        if (env('APP_ENV') === 'acceptance') {
            $this->index = 'accept' . $this->index;
        } elseif (env('APP_ENV') === 'testing') {
            $this->index = 'test' . $this->index;
        }
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

    protected function propertiesToParams(BaseModel $model)
    {
        $params = [];
        if (isset($this->model->keywords)) {
            foreach ($this->model->keywords as $keyword) {
                $params[$keyword] = $model->$keyword;
            }
        }
        if (isset($this->model->texts)) {
            foreach ($this->model->texts as $text) {
                $params[$text] = $model->$text;
            }
        }
        if (isset($this->model->integers)) {
            foreach ($this->model->integers as $integer) {
                $params[$integer] = $model->$integer;
            }
        }
        if (isset($this->model->doubles)) {
            foreach ($this->model->doubles as $double) {
                $params[$double] = $model->$double;
            }
        }
        if (isset($this->model->timestamps)) {
            foreach ($this->model->timestamps as $timestamp) {
                $params[$timestamp] = $model->$timestamp;
            }
        }
        if (isset($this->model->booleans)) {
            foreach ($this->model->booleans as $boolean) {
                $params[$boolean] = $model->$boolean;
            }
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