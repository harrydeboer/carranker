<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class ImportSQLFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importsqlfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SQL files for the first install';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sqlFilesDir = dirname(__DIR__, 3) . '/database/sql-files/';
        DB::unprepared(file_get_contents($sqlFilesDir . 'makes.sql'));
        DB::unprepared(file_get_contents($sqlFilesDir . 'models.sql'));
        DB::unprepared(file_get_contents($sqlFilesDir . 'trims.sql'));
        DB::unprepared(file_get_contents($sqlFilesDir . 'profanities.sql'));
    }
}
