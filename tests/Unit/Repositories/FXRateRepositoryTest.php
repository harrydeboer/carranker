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
}