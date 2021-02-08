<?php

declare(strict_types=1);

namespace App\Models\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticsearchClient
{
    private static Client $client;

    public static function getClient(): Client
    {
        if (!isset(self::$client) || is_null(self::$client)) {
            $hosts = [
                env('ELASTICSEARCH_HOST') . ':' . env('ELASTICSEARCH_PORT')
            ];
            if ($hosts !== [':']) {
                self::$client = ClientBuilder::create()->setHosts($hosts)->build();
            }
        }

        return self::$client;
    }
}
