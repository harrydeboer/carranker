<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

class IndexCars extends Command
{
    private $makeRepository;
    private $modelRepository;
    private $trimRepository;

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
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
    }

    public function handle()
    {
        $this->indexOrReindex($this->makeRepository);
        $this->indexOrReindex($this->modelRepository);
        $this->indexOrReindex($this->trimRepository);
    }

    private function indexOrReindex($repository)
    {
        try {
            $repository->deleteIndex();
        } catch (Missing404Exception $e) {

        }

        $repository->createIndex(null, null);
        $repository->addAllToIndex();
    }
}
