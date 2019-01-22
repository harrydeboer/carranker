<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\SitemapService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SitemapServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testSitemap()
    {
        $xmlReader = new \XMLReader();

        $sitemapService = new SitemapService();

        $result = $xmlReader->XML($sitemapService->makeSitemap(['Ford'], ['Ford;Fiesta']), NULL, LIBXML_DTDVALID);

        $this->assertTrue($result);
    }
}