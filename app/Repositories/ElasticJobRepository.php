<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ElasticJob;
use Illuminate\Database\Eloquent\Collection;

class ElasticJobRepository implements IRepository
{
    public function __construct(
        private ElasticJob $elasticJob,
    ) {
    }

    public function all(): Collection
    {
        return ElasticJob::all();
    }

    public function get(int $id): ElasticJob
    {
        return $this->elasticJob->findOrFail($id);
    }

    public function create(array $createArray): ElasticJob
    {
        $model = new ElasticJob($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        ElasticJob::destroy($id);
    }

    public function getAllMakesByAction(string $action): Collection
    {
        $jobs = $this->elasticJob->where('action', $action)->whereNotNull('make_id')->get();

        $makes = new Collection();
        foreach ($jobs as $job) {
            $makes->add($job->getMake());
            ElasticJob::destroy($job->getId());
        }

        return $makes;
    }

    public function getAllModelsByAction(string $action): Collection
    {
        $jobs = $this->elasticJob->where('action', $action)->whereNotNull('model_id')->get();

        $models = new Collection();
        foreach ($jobs as $job) {
            $models->add($job->getModel());
            ElasticJob::destroy($job->getId());
        }

        return $models;
    }

    public function getAllTrimsByAction(string $action): Collection
    {
        $jobs = $this->elasticJob->where('action', $action)->whereNotNull('trim_id')->get();

        $trims = new Collection();
        foreach ($jobs as $job) {
            $trims->add($job->getTrim());
            ElasticJob::destroy($job->getId());
        }

        return $trims;
    }

    public function truncate()
    {
        $this->elasticJob->truncate();
    }
}
