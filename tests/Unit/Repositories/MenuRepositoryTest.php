<?php

declare(strict_types=1);

use App\Models\Menu;
use App\Repositories\MenuRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $menuRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->menuRepository = new MenuRepository();
    }

    public function testGetByName()
    {
        $menu = factory(Menu::class)->create();
        $menuFromDb = $this->menuRepository->getByName($menu->getName());

        $this->assertEquals($menu->getId(), $menuFromDb->getId());
    }

    public function testSyncMenusWithCMS()
    {
        $pageHome = factory(\App\Models\Page::class)->create(['title' => 'Home', 'name' => 'home']);
        $pageContact = factory(\App\Models\Page::class)->create(['title' => 'Contact', 'name' => 'contact']);

        $menu = factory(Menu::class)->create();

        $menusCMS = new stdClass();
        $itemHome = new stdClass();
        $itemHome->title = $pageHome->getName();
        $menusCMS->navigationHeader = [$itemHome];

        $itemContact = new stdClass();
        $itemContact->title = $pageContact->getName();
        $menusCMS->navigationFooter = [$itemContact];

        $result = $this->menuRepository->syncMenusWithCMS($menusCMS);
        $this->assertTrue($result);

        $this->assertNull($this->menuRepository->find($menu->getId()));

        $menuDB = $this->menuRepository->getByName('navigationHeader');
        foreach ($menuDB->getPages()->get() as $page) {
            $this->assertEquals($page->getName(), $pageHome->getName());
            $this->assertEquals($page->getId(), $pageHome->getId());
            $this->assertEquals($page->getContent(), $pageHome->getContent());
            $this->assertEquals($page->getTitle(), $pageHome->getTitle());
        }

        $menuDB = $this->menuRepository->getByName('navigationFooter');
        foreach ($menuDB->getPages()->get() as $page) {
            $this->assertEquals($page->getName(), $pageContact->getName());
            $this->assertEquals($page->getId(), $pageContact->getId());
            $this->assertEquals($page->getContent(), $pageContact->getContent());
            $this->assertEquals($page->getTitle(), $pageContact->getTitle());
        }
    }

    public function testSyncMenusWithCMSException()
    {
        $menusCMS = new stdClass();
        $itemHome = new stdClass();
        $itemHome->title = '';
        $menusCMS->navigationHeader = [$itemHome];

        $this->expectException(\Exception::class);
        $this->menuRepository->syncMenusWithCMS($menusCMS);
    }
}