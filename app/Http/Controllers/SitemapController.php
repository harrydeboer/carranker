<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Support\Facades\View;

class SitemapController extends Controller
{
    public function makeSitemap()
    {
        $sitemap = new SitemapService();
        $sitemap = $sitemap->makeSitemap($this->makeRepository->getMakenames(), $this->modelRepository->getModelnames());

        View::share('sitemap', $sitemap);

        /** The sitemap is a xml-file so the header needs to be text/xml. The view is triggered here because
         * sitemap extends the BaseController instead of Controller which would otherwise trigger the view. */
        return response()->view('sitemap.index', compact($sitemap))->header('Content-Type', 'text/xml');
    }
}