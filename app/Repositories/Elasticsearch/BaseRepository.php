<?php

declare(strict_types=1);

namespace App\Repositories\Elasticsearch;

use App\Models\Elasticsearch\BaseModel;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    abstract public function get(int $id): BaseModel;

    public function findForSearch(string $searchString): array
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

    public function all(): array
    {
        return $this->model::all();
    }

    public function createIndex(bool $prefixTest)
    {
        $params = [
            'index' => ($prefixTest ? 'test' : '') . $this->model->getIndex(),
            'body' => [
                'settings' => $this->model->getSettings(),
                'mappings' => $this->model->getMappings(),
            ],
        ];

        // Create the index with mappings and settings now
        $this->model->indexCreate($params);
    }

    public function deleteIndex(bool $prefixTest)
    {
        $index = ($prefixTest ? 'test' : '') . $this->model->getIndex();
        $deleteParams = [
            'index' => $index,
        ];
        if ($this->model->indexExists($index)) {
            $this->model->indexDelete($deleteParams);
        }
    }

    public function indexExists(): bool
    {
        return $this->model->indexExists($this->model->getIndex());
    }

    public function getMappings(): array
    {
        return $this->model->indexGetMapping(['index' => $this->model->getIndex()]);
    }

    public function addAllToIndex(Collection $models): void
    {
        $params = ['refresh' => 'wait_for'];
        foreach ($models as $key => $model) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->model->getIndex(),
                    '_id' => $model->getId(),
                ],
            ];

            $params['body'][] = $this->model->propertiesToParams($model);

            if ($key % 1000 === 0 && $key !== 0) {
                BaseModel::bulk($params);
                $params = ['refresh' => 'wait_for'];
            }
        }

        /**
         * Send the last batch if it exists.
         */
        if (!empty($params['body'])) {
            BaseModel::bulk($params);
        }
    }

    public function updateAllInIndex(Collection $models): void
    {
        foreach ($models as $key => $model) {
            $params['body'][] = [
                'update' => [
                    '_index' => $this->model->getIndex(),
                    '_id' => $model->getId(),
                    '_type' => '_doc',
                ],
            ];
            $params['body'][] = ['doc' => $this->model->propertiesToParams($model)];

            if ($key % 1000 === 0 && $key !== 0) {
                BaseModel::bulk($params);
            }
        }

        /**
         * Send the last batch if it exists.
         */
        if (!empty($params['body'])) {
            BaseModel::bulk($params);
        }
    }

    public function deleteAllFromIndex(Collection|array $models): void
    {
        foreach ($models as $key => $model) {
            $params['body'][] = [
                'delete' => [
                    '_id' => $model->getId(),
                    '_index' => $this->model->getIndex(),
                ],
            ];

            if ($key % 1000 === 0 && $key !== 0) {
                BaseModel::bulk($params);
            }
        }

        /**
         * Send the last batch if it exists.
         */
        if (!empty($params['body']) && count($models) > 0) {
            BaseModel::bulk($params);
        }
    }
}
