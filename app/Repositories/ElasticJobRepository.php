<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ElasticJob;
use Illuminate\Database\Eloquent\Collection;

class ElasticJobRepository extends BaseRepository
{
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