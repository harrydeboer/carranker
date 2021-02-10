<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Interfaces\ElasticsearchJobRepositoryInterface;
use App\Repositories\Interfaces\MakeReadRepositoryInterface;
use App\Repositories\Interfaces\ModelReadRepositoryInterface;
use App\Repositories\Interfaces\TrimReadRepositoryInterface;
use Illuminate\Console\Command;

class ProcessQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:queue {--truncate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the elasticsearch jobs queue';

    public function __construct(
        private ElasticsearchJobRepositoryInterface $elasticJobRepository,
        private MakeReadRepositoryInterface $makeRepository,
        private ModelReadRepositoryInterface $modelRepository,
        private TrimReadRepositoryInterface $trimRepository,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        /** When updating the code in production all indices are recreated and filled and thus the queue
         * does not need to be processed and is truncated.
         */
        if ($this->option('truncate')) {

            $this->elasticJobRepository->truncate();

        } else {

            $makesCreate = $this->elasticJobRepository->getAllMakesByAction('create');
            $makesUpdate = $this->elasticJobRepository->getAllMakesByAction('update');
            $makesDelete = $this->elasticJobRepository->getAllMakesByAction('delete');

            $this->makeRepository->createAll($makesCreate);
            $this->makeRepository->updateAll($makesUpdate);
            $this->makeRepository->deleteAll($makesDelete);

            $modelsCreate = $this->elasticJobRepository->getAllModelsByAction('create');
            $modelsUpdate = $this->elasticJobRepository->getAllModelsByAction('update');
            $modelsDelete = $this->elasticJobRepository->getAllModelsByAction('delete');

            $this->modelRepository->createAll($modelsCreate);
            $this->modelRepository->updateAll($modelsUpdate);
            $this->modelRepository->deleteAll($modelsDelete);

            $trimsCreate = $this->elasticJobRepository->getAllTrimsByAction('create');
            $trimsUpdate = $this->elasticJobRepository->getAllTrimsByAction('update');
            $trimsDelete = $this->elasticJobRepository->getAllTrimsByAction('delete');

            $this->trimRepository->createAll($trimsCreate);
            $this->trimRepository->updateAll($trimsUpdate);
            $this->trimRepository->deleteAll($trimsDelete);
        }

        $this->info('Queue processed!');
    }
}
