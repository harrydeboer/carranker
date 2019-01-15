<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

class BaseRepository
{
    protected $modelClassName;

    public function __construct()
    {
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = 'App\Models\Elastic\\' . str_replace('Repository', '', end($classNameArray));
    }

    public function reindex()
    {
        $this->modelClassName::reindex();
    }

    public function createIndex($shards = null, $replicas = null)
    {
        $this->modelClassName::createIndex($shards, $replicas);
    }

    public function deleteIndex()
    {
        $this->modelClassName::deleteIndex();
    }

    public function putMappingTrait($ignoreConflicts)
    {
        $this->modelClassName::putMapping($ignoreConflicts);
    }

    public function mappingExists(): bool
    {
        return $this->modelClassName::mappingExists();
    }

    public function rebuildMapping()
    {
        $this->modelClassName::rebuildMapping();
    }

    public function deleteMapping()
    {
        $this->modelClassName::deleteMapping();
    }

    public function addAllToIndex()
    {
        $this->modelClassName::addAllToIndex();
    }

    public function getByName(array $query)
    {
        return $this->modelClassName::searchByQuery($query)->first();
    }
}