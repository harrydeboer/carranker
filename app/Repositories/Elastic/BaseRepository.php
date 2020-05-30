<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use App\Models\Make;
use Elasticsearch\ClientBuilder;

abstract class BaseRepository
{
    protected $client;

    /** The child of this base repository has a model. The modelname is stored in the property modelClassName. */
    public function __construct()
    {
        $hosts = [
            env('ELASTIC_HOST') . ':' . env('ELASTIC_PORT')
        ];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
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
}