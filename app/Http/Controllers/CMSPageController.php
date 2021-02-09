<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Http\Response;

class CMSPageController extends Controller
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
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
