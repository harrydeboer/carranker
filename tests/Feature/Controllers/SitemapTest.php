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

        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
        $response->assertSee('<loc>', false);
        $response->assertSee('<priority>', false);
        $response->assertSee('<lastmod>', false);
        $response->assertSee('<changefreq>', false);
        $response->assertSee('<url>', false);
    }
}