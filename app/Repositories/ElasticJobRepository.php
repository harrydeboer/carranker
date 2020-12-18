<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ElasticJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ElasticJobRepository implements IRepository
{
    public function all(): Collection
    {
        return ElasticJob::all();
    }

    public function find(int $id): ?ElasticJob
    {
        return ElasticJob::find($id);
    }

    public function get(int $id): ElasticJob
    {
        return ElasticJob::findOrFail($id);
    }

    public function create(array $createArray): ElasticJob
    {
        $model = new ElasticJob($createArray);
        $model->save();

        return $model;
    }

    public function update(Model $model): void
    {
        $model->save();
    }

    public function delete(int $id): void
    {
        ElasticJob::destroy($id);
    }

    public function getAllMakesByAction(string $action): Collection
    {
        $jobs = ElasticJob::where('action', $action)->whereNotNull('make_id')->get();

        $makes = new Collection();
        foreach ($jobs as $job) {
            $makes->add($job->getMake());
        }

        return $makes;
    }

    public function getAllModelsByAction(string $action): Collection
    {
        $jobs = ElasticJob::where('action', $action)->whereNotNull('model_id')->get();

        $models = new Collection();
        foreach ($jobs as $job) {
            $models->add($job->getModel());
        }

        return $models;
    }

    public function getAllTrimsByAction(string $action): Collection
    {
        $jobs = ElasticJob::where('action', $action)->whereNotNull('trim_id')->get();

        $trims = new Collection();
        foreach ($jobs as $job) {
            $trims->add($job->getTrim());
        }

        return $trims;
    }

    public function truncate()
    {
        ElasticJob::truncate();
    }
}
