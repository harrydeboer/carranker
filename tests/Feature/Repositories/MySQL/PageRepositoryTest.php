<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\Page;
use App\Repositories\MySQL\PageRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->assertNull($this->pageRepository->findByName('doesNotExist'));
    }

    public function testGetByName()
    {
        $page = Page::factory()->create();
        $pageFromDb = $this->pageRepository->getByName($page->getName());

        $this->assertEquals($page->getId(), $pageFromDb->getId());

        $this->expectException(NotFoundHttpException::class);

        $this->pageRepository->getByName('doesNotExist');
    }
}
