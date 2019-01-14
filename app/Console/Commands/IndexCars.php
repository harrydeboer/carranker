<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Elastic\Make;
use Illuminate\Console\Command;

class IndexCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'IndexCars';

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Make::createIndex(null, null);

        Make::putMapping(true);

        Make::addAllToIndex();
    }
}
