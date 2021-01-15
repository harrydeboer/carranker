<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Page;
use App\Repositories\PageRepository;
use Tests\FeatureTestCase;

class PageRepositoryTest extends FeatureTestCase
{
    private PageRepository $pageRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pageRepository = $this->app->make(PageRepository::class);
    }

    public function testFindByName()
    {
        $page = Page::factory()->create();
        $pageFromDb = $this->pageRepository->findByName($page->getName());

        $this->assertEquals($page->getId(), $pageFromDb->getId());
    }
}
