<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PageRepository;
use Illuminate\Http\Response;

class CmsController extends Controller
{
    public function __construct(private PageRepository $pageRepository){}

    public function view(string $url): Response
    {
        $page = $this->pageRepository->getByName($url);

        $response = response()->view('cms.index', [
            'title' => $page->title,
            'page' => $page,
        ],200);

        return $response;
    }
}
