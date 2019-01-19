<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

abstract class BaseRepository
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

    public function createIndex(int $shards = null, int $replicas = null)
    {
        $this->modelClassName::createIndex($shards, $replicas);
    }

    public function deleteIndex()
    {
        $this->modelClassName::deleteIndex();
    }

    public function putMappingTrait(bool $ignoreConflicts)
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
}