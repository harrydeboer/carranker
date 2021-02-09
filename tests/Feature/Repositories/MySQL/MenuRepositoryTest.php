<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use Tests\FeatureTestCase;

class MenuRepositoryTest extends FeatureTestCase
{
    private MenuRepositoryInterface $menuRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menuRepository = $this->app->make(MenuRepositoryInterface::class);
    }

    public function testFindByName()
    {
        $menu = Menu::factory()->create();
        $menuFromDb = $this->menuRepository->findByName($menu->getName());

        $this->assertEquals($menu->getId(), $menuFromDb->getId());
    }
}
