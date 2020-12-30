<?php

declare(strict_types=1);

namespace App;

use Elasticsearch\ClientBuilder;

class ElasticClient
{
    private static $client;

    public static function getClient()
    {
        if (is_null(self::$client)) {
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