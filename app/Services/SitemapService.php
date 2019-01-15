<?php

declare(strict_types=1);

namespace App\Services;

use SimpleXMLElement;

class SitemapService
{
    public function makeSitemap(array $makenames, array $modelnames)
    {
        $sitemap = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        $pages = ['', 'contact', 'about', 'login', 'register', 'navigate'];

        $env = env('APP_ENV');

        switch ($env) {
            case 'local':
                $baseUrl = "http://carranker";
                break;
            case 'production':
                $baseUrl = "https://carranker.com";
                break;
            case 'acceptance':
                $baseUrl = "https://accept.carranker.com";
                break;
        }

        $update = '2017-10-08';

        foreach ($pages as $page) {
            $url = $sitemap->addChild('url');
            $url->addChild('loc', $baseUrl . '/' . $page);
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        foreach ($makenames as $make) {
            $url = $sitemap->addChild('url');
            $url->addChild('loc', $baseUrl . '/make/' . str_replace(' ', '_', $make));
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        foreach ($modelnames as $modelname) {
            $modelArray = explode(';', $modelname);
            $url = $sitemap->addChild('url');
            $subUrl = str_replace(' ', '_', $modelArray[0]) . '/' . str_replace(' ', '_', $modelArray[1]);
            $url->addChild('loc', $baseUrl . '/model/' . $subUrl);
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        return $sitemap->saveXML();
    }
}
