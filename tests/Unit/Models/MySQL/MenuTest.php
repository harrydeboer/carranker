<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\MySQL\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuTest extends TestCase
{
    use DatabaseMigrations;

    public function testMenuNameInDB()
    {
        $menu = Menu::factory()->create();

        $this->assertDatabaseHas('menus', [
            'id' => $menu->getId(),
            'name' => $menu->getName(),
        ]);

        $menuDb = (new Menu())->find($menu->getId());
        $this->assertTrue($menuDb->testAttributesMatchFillable());
    }
}
