<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Illuminate\Console\Command;

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
    'When testing is true the test indices are deleted and created and no documents are indexed.';

    public function __construct(
        private MakeRepository $makeRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
        private \App\Repositories\MakeRepository $makeRepositoryEloquent,
        private \App\Repositories\ModelRepository $modelRepositoryEloquent,
        private \App\Repositories\TrimRepository $trimRepositoryEloquent,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $testing = $this->option('testing');
        if (env('APP_ENV') === 'testing') {
            $prefixTest = false;
        } else {
            $prefixTest = $testing;
        }

        $this->makeRepository->deleteIndex($prefixTest);
        $this->modelRepository->deleteIndex($prefixTest);
        $this->trimRepository->deleteIndex($prefixTest);

        $this->makeRepository->createIndex($prefixTest);
        $this->modelRepository->createIndex($prefixTest);
        $this->trimRepository->createIndex($prefixTest);

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
