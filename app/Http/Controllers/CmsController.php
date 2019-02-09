<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class CmsController extends BaseController
{
    protected $title;

    public function view(string $url): Response
    {
        $cacheString = ucfirst($url);
        $this->title = ucfirst($url);

        if ($this->redis->get($cacheString) !== false) {

            return response($this->redis->get($cacheString), 200);
        }

        $page = $this->pageRepository->getByName($url);
        $this->title = $page->title;

        $response = response()->view('cms.index', [
            'page' => $page,
        ],200);

        $this->redis->set($cacheString, $response->getContent(), $this->cacheExpire);

        return $response;
    }
}