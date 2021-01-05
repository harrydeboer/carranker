<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use Illuminate\Console\Command;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use \Exception;
use Illuminate\Support\Facades\DB;

class GetCMSData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getcmsdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get CMS data';

    public function __construct(private Mailer $mailer,
                                private PageRepository $pageRepository,
                                private MenuRepository $menuRepository)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        DB::unprepared(file_get_contents(base_path() . '/database/sql-files/menus.sql'));
        DB::unprepared(file_get_contents(base_path() . '/database/sql-files/pages.sql'));
        DB::unprepared(file_get_contents(base_path() . '/database/sql-files/menus_pages.sql'));
    }
}
