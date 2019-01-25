<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class SitemapController extends Controller
{
    public function makeSitemap(): Response
    {
        $sitemap = new SitemapService();
        $sitemap = $sitemap->makeSitemap($this->makeRepository->getMakeNames(), $this->modelRepository->getModelNames());

        View::share('sitemap', $sitemap);

        /** The sitemap is a xml-file so the header needs to be text/xml. */
        return response()->view('sitemap.index', compact($sitemap))->header('Content-Type', 'text/xml');
    }
}