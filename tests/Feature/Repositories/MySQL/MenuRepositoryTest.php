<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\Menu;
use App\Repositories\MySQL\MenuRepository;
use Tests\FeatureTestCase;

class MenuRepositoryTest extends FeatureTestCase
{
    private MenuRepository $menuRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menuRepository = $this->app->make(MenuRepository::class);
    }

    public function testFindByName()
    {
        $menu = Menu::factory()->create();
        $menuFromDb = $this->menuRepository->findByName($menu->getName());

        $this->assertEquals($menu->getId(), $menuFromDb->getId());
    }
}
