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
    protected $signature = 'index:cars {--only-delete-create-indices}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index makes, models and trims in elasticsearch';

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
        $this->makeRepository->deleteIndex();
        $this->modelRepository->deleteIndex();
        $this->trimRepository->deleteIndex();

        $this->makeRepository->createIndex();
        $this->modelRepository->createIndex();
        $this->trimRepository->createIndex();

        if (!$this->option('only-delete-create-indices')) {
            $this->makeRepository->addAllToIndex($this->makeRepositoryEloquent->all());
            $this->modelRepository->addAllToIndex($this->modelRepositoryEloquent->all());
            $this->trimRepository->addAllToIndex($this->trimRepositoryEloquent->all());
        }

        $this->info('Cars indexed!');
    }
}
