<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\MySQL\Page;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PageTest extends TestCase
{
    use DatabaseMigrations;

    public function testPageInDB()
    {
        $page = Page::factory()->create();

        $this->assertDatabaseHas('pages', [
            'id' => $page->getId(),
            'name' => $page->getName(),
            'content' => $page->getContent(),
        ]);

        $pageDb = (new Page())->find($page->getId());
        $this->assertTrue($pageDb->testAttributesMatchFillable());
    }
}
