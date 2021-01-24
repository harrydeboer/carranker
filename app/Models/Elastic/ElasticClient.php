<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticClient
{
    private static Client $client;

    public static function getClient(): Client
    {
        if (!isset(self::$client) || is_null(self::$client)) {
            $hosts = [
                env('ELASTIC_HOST') . ':' . env('ELASTIC_PORT')
            ];
            if ($hosts !== [':']) {
                self::$client = ClientBuilder::create()->setHosts($hosts)->build();
            }
        }

        return self::$client;
    }
}
