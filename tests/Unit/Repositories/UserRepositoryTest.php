<?php

declare(strict_types=1);

use App\Models\Page;
use App\Repositories\PageRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PageRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $pageRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->pageRepository = new PageRepository();
    }

    public function testGetByName()
    {
        $page = factory(Page::class)->create();
        $pageFromDb = $this->pageRepository->getByName($page->getName());

        $this->assertEquals($page->getId(), $pageFromDb->getId());
    }
}