<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class CmsController extends Controller
{
    public function view($url)
    {
        $page = $this->pageRepository->getByName($url);

        $data = ['controller' => 'cms', 'title' => $page->title, 'page' => $page];

        return View::make('cms.index')->with($data);
    }
}