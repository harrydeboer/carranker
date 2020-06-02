<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Page;
use App\Repositories\PageRepository;
use Tests\TestCase;

class PageRepositoryTest extends TestCase
{
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

    /** The phpinfo page is in the cms, but must not be in the laravel pages table. */
    public function testSyncPagesWithCMS()
    {
        $page = factory(Page::class)->create();
        $pagesCMS = [];
        $pagesCMS[] = $this->makePageCMS('home', 'Home', 'Content');
        $pagesCMS[] = $this->makePageCMS('contact', 'Contact', 'Content');
        $pagesCMS[] = $this->makePageCMS('auth', 'Authentication', 'Content');
        $pagesCMS[] = $this->makePageCMS('register', 'Register', 'Content');
        $pagesCMS[] = $this->makePageCMS('phpinfo', 'PHPInfo', 'Content');

        $result = $this->pageRepository->syncPagesWithCMS($pagesCMS);

        $this->assertTrue($result);
        $this->assertNull($this->pageRepository->find($page->getId()));

        foreach ($pagesCMS as $pageCMS) {
        	if ($pageCMS->slug !== 'phpinfo') {
		        $pageDB = $this->pageRepository->getByName( $pageCMS->slug );
		        $this->assertEquals( $pageDB->getName(), $pageCMS->slug );
	        }
        }
    }

    public function testSyncPagesWithCMSException()
    {
        factory(Page::class)->create();
        $pagesCMS = [];
        $pagesCMS[] = $this->makePageCMS('home', 'Home', 'Content');
        $this->expectException(\Exception::class);
        $this->pageRepository->syncPagesWithCMS($pagesCMS);
    }

    private function makePageCMS(string $slug, string $title, string $content)
    {
        $pageCMS = new \stdClass();
        $pageCMS->slug = $slug;
        $pageCMS->title = new \stdClass();
        $pageCMS->title->rendered = $title;
        $pageCMS->content = new \stdClass();
        $pageCMS->content->rendered = $content;

        return $pageCMS;
    }
}