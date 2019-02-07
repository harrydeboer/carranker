<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class CmsController extends Controller
{
    public function view(string $url): Response
    {
        $this->decorator();
        $page = $this->pageRepository->getByName($url);

        return response()->view('cms.index', [
            'controller' => 'cms',
            'title' => $page->title,
            'page' => $page,
        ],200);
    }
}