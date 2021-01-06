<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class PageRepositoryTest extends TestCase
{
    private $pageRepository;

    public function setUp(): void
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
