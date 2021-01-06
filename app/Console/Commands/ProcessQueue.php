<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use App\Repositories\ElasticJobRepository;
use Illuminate\Console\Command;

class ProcessQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processqueue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the elasticsearch jobs queue';

    public function __construct(private ElasticJobRepository $elasticJobRepository,
                                private MakeRepository $makeRepository,
                                private ModelRepository $modelRepository,
                                private TrimRepository $trimRepository)
    {
        parent::__construct();
    }

    public function handle()
    {
        $makesCreate = $this->elasticJobRepository->getAllMakesByAction('create');
        $makesUpdate = $this->elasticJobRepository->getAllMakesByAction('update');
        $makesDelete = $this->elasticJobRepository->getAllMakesByAction('delete');

        $this->makeRepository->addAllToIndex($makesCreate);
        $this->makeRepository->updateAllInIndex($makesUpdate);
        $this->makeRepository->deleteAllFromIndex($makesDelete);

        $modelsCreate = $this->elasticJobRepository->getAllModelsByAction('create');
        $modelsUpdate = $this->elasticJobRepository->getAllModelsByAction('update');
        $modelsDelete = $this->elasticJobRepository->getAllModelsByAction('delete');

        $this->modelRepository->addAllToIndex($modelsCreate);
        $this->modelRepository->updateAllInIndex($modelsUpdate);
        $this->modelRepository->deleteAllFromIndex($modelsDelete);

        $trimsCreate = $this->elasticJobRepository->getAllTrimsByAction('create');
        $trimsUpdate = $this->elasticJobRepository->getAllTrimsByAction('update');
        $trimsDelete = $this->elasticJobRepository->getAllTrimsByAction('delete');

        $this->trimRepository->addAllToIndex($trimsCreate);
        $this->trimRepository->updateAllInIndex($trimsUpdate);
        $this->trimRepository->deleteAllFromIndex($trimsDelete);

        $this->elasticJobRepository->truncate();

        $this->info('Queue processed!');
    }
}
