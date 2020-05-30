<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\BaseModel;
use App\Models\Make;
use Elasticsearch\ClientBuilder;

abstract class BaseRepository
{
    protected $client;
    protected $model;

    /** The child of this base repository has a model. The modelname is stored in the property modelClassName. */
    public function __construct()
    {
        $hosts = [
            env('ELASTIC_HOST') . ':' . env('ELASTIC_PORT')
        ];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();

        $classNameArray = explode('\\', static::class);
        $modelClassName = 'App\Models\Elastic\\' . str_replace('Repository', '', end($classNameArray));
        $this->model = new $modelClassName();
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

        return $params;
    }
}