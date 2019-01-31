<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function testSitemap()
    {
        $response = $this->get('/api/sitemap');
        $response->assertStatus(200);

        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        $response->assertSee('<loc>');
        $response->assertSee('<priority>');
        $response->assertSee('<lastmod>');
        $response->assertSee('<changefreq>');
        $response->assertSee('<url>');
    }
}