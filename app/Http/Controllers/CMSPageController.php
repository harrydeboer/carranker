<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MySQL\PageRepository;
use Illuminate\Http\Response;

class CMSPageController extends Controller
{
    public function __construct(
        private PageRepository $pageRepository,
    ) {
    }

    public function view(string $url): Response
    {
        $page = $this->pageRepository->getByName($url);

        return response()->view('cMSPage.index', [
            'title' => $page->getTitle(),
            'content' => $page->getContent(),
        ]);
    }
}
