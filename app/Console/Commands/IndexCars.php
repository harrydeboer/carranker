<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Env;

class IndexCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:cars {--testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index makes, models and trims in elasticsearch. ' .
    'When testing is true the test indices are deleted and created.';

    public function __construct(
        private MakeRepository $makeRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private \App\Repositories\MakeRepository $makeRepositoryEloquent,
        private \App\Repositories\ModelRepository $modelRepositoryEloquent,
        private \App\Repositories\TrimRepository $trimRepositoryEloquent,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $testing = $this->option('testing');

        $this->makeRepository->deleteIndex($testing);
        $this->modelRepository->deleteIndex($testing);
        $this->trimRepository->deleteIndex($testing);

        $this->makeRepository->createIndex($testing);
        $this->modelRepository->createIndex($testing);
        $this->trimRepository->createIndex($testing);

        if (!$testing) {
            $this->makeRepository->addAllToIndex($this->makeRepositoryEloquent->all());
            $this->modelRepository->addAllToIndex($this->modelRepositoryEloquent->all());
            $this->trimRepository->addAllToIndex($this->trimRepositoryEloquent->all());

            $this->info('Cars indexed!');
        } else {
            $this->info('Indices deleted and created!');
        }
    }
}
