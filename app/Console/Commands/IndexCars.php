<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;

class IndexCars extends Command
{
    private $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexcars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index makes, models and trims in elasticsearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $hosts = [
            env('ELASTIC_HOST') . ':' . env('ELASTIC_PORT')
        ];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function handle()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id',
            'body'  => ['testField' => 'abc']
        ];

        $response = $this->client->index($params);
        var_dump($response);
    }
}