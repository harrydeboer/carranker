<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Interfaces\MakeReadRepositoryInterface;
use App\Repositories\Interfaces\ModelReadRepositoryInterface;
use App\Repositories\Interfaces\TrimReadRepositoryInterface;
use Illuminate\Console\Command;

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
        private MakeReadRepositoryInterface $makeRepository,
        private ModelReadRepositoryInterface $modelRepository,
        private TrimReadRepositoryInterface $trimRepository,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->makeRepository->deleteAll($this->makeRepository->all());
        $this->modelRepository->deleteAll($this->modelRepository->all());
        $this->trimRepository->deleteAll($this->trimRepository->all());

        $this->info('Indices flushed!');
    }
}
