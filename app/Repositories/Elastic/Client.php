<?php

declare(strict_types=1);

namespace App\Repositories\Elastic;

use Elasticsearch\ClientBuilder;

class Client
{
    private static $client;

    public static function getClient()
    {
        if (is_null(self::$client)) {
            $hosts = [
                env('ELASTIC_HOST') . ':' . env('ELASTIC_PORT')
            ];
            self::$client = ClientBuilder::create()->setHosts($hosts)->build();
        }

        return self::$client;
    }
}