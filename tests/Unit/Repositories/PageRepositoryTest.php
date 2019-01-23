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

    public function testSyncPagesWithCMS()
    {
        $page = factory(Page::class)->create();
        $pagesCMS = [];
        $pagesCMS[] = $this->makePageCMS('home', 'Home', 'Content');
        $pagesCMS[] = $this->makePageCMS('contact', 'Contact', 'Content');
        $pagesCMS[] = $this->makePageCMS('login', 'Login', 'Content');
        $pagesCMS[] = $this->makePageCMS('register', 'Register', 'Content');
        $pagesCMS[] = $this->makePageCMS('opcachereset', 'OPcacheReset', 'Content');

        $result = $this->pageRepository->syncPagesWithCMS($pagesCMS);

        $this->assertFalse($result === "");

        $pagesCMS[] = $this->makePageCMS('phpinfo', 'PHPInfo', 'Content');
        $result = $this->pageRepository->syncPagesWithCMS($pagesCMS);

        $this->assertEquals($result, "");
        $this->assertNull($this->pageRepository->find($page->getId()));

        foreach ($pagesCMS as $pageCMS) {
            $pageDB = $this->pageRepository->getByName($pageCMS->slug);
            $this->assertEquals($pageDB->getName(), $pageCMS->slug);
        }
    }

    private function makePageCMS(string $slug, string $title, string $content)
    {
        $pageCMS = new stdClass();
        $pageCMS->slug = $slug;
        $pageCMS->title = new stdClass();
        $pageCMS->title->rendered = $title;
        $pageCMS->content = new stdClass();
        $pageCMS->content->rendered = $content;

        return $pageCMS;
    }
}