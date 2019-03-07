<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

abstract class BaseRepository
{
    protected $modelClassName;

    /** The child of this base repository has a model. The modelname is stored in the property modelClassName. */
    public function __construct()
    {
        $classNameArray = explode('\\', static::class);
        $this->modelClassName = 'App\Models\Elastic\\' . str_replace('Repository', '', end($classNameArray));
    }

    public function reindex(): void
    {
        $this->modelClassName::reindex();
    }

    public function createIndex(int $shards = null, int $replicas = null): void
    {
        $this->modelClassName::createIndex($shards, $replicas);
    }

    public function deleteIndex(): void
    {
        $this->modelClassName::deleteIndex();
    }

    public function putMappingTrait(bool $ignoreConflicts): void
    {
        $this->modelClassName::putMapping($ignoreConflicts);
    }

    public function mappingExists(): bool
    {
        return $this->modelClassName::mappingExists();
    }

    public function rebuildMapping(): void
    {
        $this->modelClassName::rebuildMapping();
    }

    public function deleteMapping(): void
    {
        $this->modelClassName::deleteMapping();
    }

    public function addAllToIndex(): void
    {
        $this->modelClassName::addAllToIndex();
    }
}