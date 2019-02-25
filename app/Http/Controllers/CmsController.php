<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class CmsController extends BaseController
{
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