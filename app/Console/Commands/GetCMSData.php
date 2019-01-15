<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class GetCMSData extends Command
{
    private $pageRepository;
    private $menuRepository;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageRepository = new PageRepository();
        $this->menuRepository = new MenuRepository();
    }

    public function handle()
    {
        $ch = curl_init();

        $env = env('APP_ENV');

        switch ($env) {
            case 'local':
                $baseUrl = "http://cms.carranker";
                break;
            case 'testing':
                $baseUrl = "http://cms.carranker";
                break;
            case 'production':
                $baseUrl = "https://cms.carranker.com";
                break;
            case 'acceptance':
                $baseUrl = "https://accept.cms.carranker.com";
                break;
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $baseUrl . "/wp-json/jwt-auth/v1/token");
        curl_setopt($ch, CURLOPT_POSTFIELDS,"username=" . env('WP_ADMIN_USERNAME') . "&password=" . env('WP_ADMIN_PASSWORD'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $token = json_decode($response);

        curl_close($ch);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $header = ["Authorization: Bearer " . $token->token];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $baseUrl . "/wp-json/wp/v2/pages");

        $output = curl_exec($ch);

        $pagesCMS = json_decode($output);

        curl_setopt($ch, CURLOPT_URL, $baseUrl . "/wp-json/myroutes/allmenus");

        $output = curl_exec($ch);

        $menus = json_decode($output);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $errors = "";
        if ($httpCode != 200) {
            $errors .= "Error: The Wordpress API is not available.";
        }

        $errors .= $this->pageRepository->syncPagesWithCMS($pagesCMS);

        if (!isset($menus->navigationHeader) || empty($menus->navigationHeader) ||
            !isset($menus->navigationFooter) || empty($menus->navigationFooter)) {
            $errors .= "Error: Necessary menu(s)/menuitem(s) deleted.";
        }

        $this->menuRepository->syncMenusWithCMS($menus);

        if ($errors !== "") {
            Mail::send('contact.message', ['userMessage' => $errors], function (Message $message) {
                $message->from(env('MAIL_POSTMASTER_USERNAME'), 'Postmaster');
                $message->subject('Wordpress api error');
                $message->to(env('MAIL_USERNAME'));
            });
        }
    }
}
