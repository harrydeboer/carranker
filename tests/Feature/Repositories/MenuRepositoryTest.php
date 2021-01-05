<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Menu;
use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class MenuRepositoryTest extends TestCase
{
    private MenuRepository $menuRepository;
    private PageRepository $pageRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->menuRepository = $this->app->make(MenuRepository::class);
        $this->pageRepository = $this->app->make(PageRepository::class);
    }

    public function testGetByName()
    {
        $menu = Menu::factory()->create();
        $menuFromDb = $this->menuRepository->getByName($menu->getName());

        $this->assertEquals($menu->getId(), $menuFromDb->getId());
    }
}
