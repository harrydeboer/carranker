<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PageRepository;
use Illuminate\Http\Response;

class CMSPageController extends Controller
{
    public function __construct(
        private PageRepository $pageRepository,
    ) {
    }

    public function view(string $url): Response
    {
        $page = $this->pageRepository->findByName($url);

        if (is_null($page)) {
            abort(404, 'The requested page does not exist.');
        }

        return response()->view('cMSPage.index', [
            'title' => $page->getTitle(),
            'content' => $page->getContent(),
        ]);
    }
}
