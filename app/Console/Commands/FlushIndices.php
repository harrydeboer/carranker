<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Elasticsearch\MakeRepository;
use App\Repositories\Elasticsearch\ModelRepository;
use App\Repositories\Elasticsearch\TrimRepository;
use Illuminate\Console\Command;

/** @noinspection PhpUnused */
class FlushIndices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:indices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush all indices in elasticsearch';

    public function __construct(
        private MakeRepository $makeRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->makeRepository->deleteAllFromIndex($this->makeRepository->all());
        $this->modelRepository->deleteAllFromIndex($this->modelRepository->all());
        $this->trimRepository->deleteAllFromIndex($this->trimRepository->all());

        $this->info('Indices flushed!');
    }
}
