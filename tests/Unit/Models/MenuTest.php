<?php

namespace Tests\Unit\Models;

use App\Models\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuTest extends TestCase
{
    use DatabaseMigrations;

    public function testMenuNameInDB()
    {
        $menu = Menu::factory()->create();

        $this->assertDatabaseHas('menus', [
            'name' => $menu->getName(),
        ]);

        $menuDB = Menu::find($menu->getId());
        $this->assertTrue($menuDB->testAttributesMatchFillable());
    }
}
