<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PageRepository;
use SimpleXMLElement;

class SitemapService
{
    public function __construct(
        private PageRepository $pageRepository,
    ){}

    public function makeSitemap(array $makeNames, array $modelNames): string
    {
        $sitemap = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
                <?xml-stylesheet type="text/xsl" href="' . fileUrl('/css/sitemap.xsl') . '"?>
                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        $pages = $this->pageRepository->all();

        $env = env('APP_ENV');

        $baseUrl = match ($env) {
            'local', 'testing' => "http://carranker",
            'production' => "https://carranker.com",
            'acceptance' => "https://accept.carranker.com",
        };

        $update = '2019-01-31';

        foreach ($pages as $page) {
            $url = $sitemap->addChild('url');
            if ($page->getName() === 'home') {
	            $url->addChild( 'loc', $baseUrl . '/' );
            } else {
	            $url->addChild( 'loc', $baseUrl . '/' . $page->getName() );
            }
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        foreach ($makeNames as $makeName) {
            $url = $sitemap->addChild('url');
            $url->addChild('loc', $baseUrl . '/make/' . rawurlencode($makeName));
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        foreach ($modelNames as $modelName) {
            $modelArray = explode(';', $modelName);
            $url = $sitemap->addChild('url');
            $subUrl = rawurlencode($modelArray[0]) . '/' . rawurlencode($modelArray[1]);
            $url->addChild('loc', $baseUrl . '/model/' . $subUrl);
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        return $sitemap->saveXML();
    }
}
