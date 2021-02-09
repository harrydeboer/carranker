<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\ElasticsearchJob;
use App\Repositories\Interfaces\ElasticJobRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ElasticJobRepository implements ElasticJobRepositoryInterface
{
    public function __construct(
        private ElasticsearchJob $elasticJob,
    ) {
    }

    public function all(): Collection
    {
        return ElasticsearchJob::all();
    }

    public function get(int $id): ElasticsearchJob
    {
        return $this->elasticJob->findOrFail($id);
    }

    public function create(array $createArray): ElasticsearchJob
    {
        $model = new ElasticsearchJob($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        ElasticsearchJob::destroy($id);
    }

    public function getAllMakesByAction(string $action): Collection
    {
        $jobs = $this->elasticJob->where('action', $action)->whereNotNull('make_id')->get();

        $makes = new Collection();
        foreach ($jobs as $job) {
            $makes->add($job->getMake());
            ElasticsearchJob::destroy($job->getId());
        }

        return $makes;
    }

    public function getAllModelsByAction(string $action): Collection
    {
        $jobs = $this->elasticJob->where('action', $action)->whereNotNull('model_id')->get();

        $models = new Collection();
        foreach ($jobs as $job) {
            $models->add($job->getModel());
            ElasticsearchJob::destroy($job->getId());
        }

        return $models;
    }

    public function getAllTrimsByAction(string $action): Collection
    {
        $jobs = $this->elasticJob->where('action', $action)->whereNotNull('trim_id')->get();

        $trims = new Collection();
        foreach ($jobs as $job) {
            $trims->add($job->getTrim());
            ElasticsearchJob::destroy($job->getId());
        }

        return $trims;
    }

    public function truncate(): void
    {
        $this->elasticJob->truncate();
    }
}
