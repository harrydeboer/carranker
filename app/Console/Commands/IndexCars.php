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
    protected $signature = 'indexcars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index makes, models and trims in elasticsearch';

    public function __construct(private MakeRepository $makeRepository,
                                private ModelRepository $modelRepository,
                                private TrimRepository $trimRepository)
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

        $this->makeRepository->addAllToIndex();
        $this->modelRepository->addAllToIndex();
        $this->trimRepository->addAllToIndex();

        $this->info('Cars indexed!');
    }
}
