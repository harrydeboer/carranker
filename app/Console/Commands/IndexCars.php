<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
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
        $this->makeRepository->deleteIndex();
        $this->modelRepository->deleteIndex();
        $this->trimRepository->deleteIndex();

        $this->makeRepository->createIndex();
        $this->makeRepository->addAllToIndex();
        $this->modelRepository->createIndex();
        $this->modelRepository->addAllToIndex();
        $this->trimRepository->createIndex();
        $this->trimRepository->addAllToIndex();

        $this->info('Cars indexed!');
    }
}