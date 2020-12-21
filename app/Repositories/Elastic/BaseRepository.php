<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\BaseModel as EloquentBaseModel;
use App\Models\Elastic\BaseModel;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected BaseModel $model;

    abstract public function get(int $id): BaseModel;

    public function findForSearch(string $searchString): Collection
    {
        $words = explode(' ', $searchString);

        $params = [
            'index' => $this->model->getIndex(),
            'size' => 100,
            'sort' => ['name:asc'],
        ];

        foreach ($words as $word) {
            $params['body']['query']['bool']['should'][] = ['wildcard' => ['name' => '*' . $word . '*']];
        }

        return $this->model::searchMany($params);
    }

    public function createIndex()
    {
        $params = [
            'index' => $this->model->getIndex(),
            'body' => [
                'settings' => $this->model->getSettings(),
                'mappings' => $this->model->getMappings(),
            ],
        ];

        // Create the index with mappings and settings now
        BaseModel::indicesCreate($params);
    }

    public function deleteIndex()
    {
        $deleteParams = [
            'index' => $this->model->getIndex(),
        ];
        if (BaseModel::indicesExists($deleteParams)) {
            BaseModel::indicesDelete($deleteParams);
        }
    }

    public function getMappings(): array
    {
        return BaseModel::indicesGetMapping(['index' => $this->model->getIndex()]);
    }

    protected function propertiesToParams(EloquentBaseModel $model): array
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

    public function addAllToIndex(Collection $models): void
    {
        foreach ($models as $key => $model) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->model->getIndex(),
                    '_id' => $model->getId(),
                ]
            ];

            $params['body'][] = $this->propertiesToParams($model);

            if ($key % 1000 === 0) {
                BaseModel::bulk($params);
                unset($params);
            }
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            BaseModel::bulk($params);
        }
    }

    public function updateAllInIndex(Collection $models): void
    {
        foreach ($models as $key => $model) {
            $params = [
                'index' => $this->model->getIndex(),
                'id' => $model->getId(),
            ];

            $params['body']['doc'] = $this->propertiesToParams($model);

            BaseModel::updateInIndex($params);
        }
    }

    public function deleteAllFromIndex(Collection $models): void
    {
        foreach ($models as $key => $model) {
            $params = [
                'index' => $this->model->getIndex(),
                'id' => $model->getId(),
            ];

            BaseModel::deleteFromIndex($params);
        }
    }
}
