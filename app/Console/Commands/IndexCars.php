<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elastic\BaseRepository;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

class IndexCars extends Command
{
    private $baseRepository;
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
        $this->baseRepository = new BaseRepository();
        $this->makeRepository = new MakeRepository();
        $this->modelRepository = new ModelRepository();
        $this->trimRepository = new TrimRepository();
    }

    public function handle()
    {
        try {
            $this->makeRepository->deleteIndex();
            $this->makeRepository->createIndex(null, null);
            $this->makeRepository->addAllToIndex();
        } catch (Missing404Exception $e) {
            $this->makeRepository->reindex();
        }

        try {
            $this->modelRepository->deleteIndex();
            $this->modelRepository->createIndex(null, null);
            $this->modelRepository->addAllToIndex();
        } catch (Missing404Exception $e) {
            $this->modelRepository->reindex();
        }

        try {
            $this->trimRepository->deleteIndex();
            $this->trimRepository->createIndex(null, null);
            $this->trimRepository->addAllToIndex();
        } catch (Missing404Exception $e) {
            $this->trimRepository->reindex();
        }
    }
}
