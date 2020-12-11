<?php

namespace Tests\Unit\Models;

use App\Models\Page;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PageTest extends TestCase
{
    use DatabaseMigrations;

    public function testPageInDB()
    {
        $page = Page::factory()->create();

        $this->assertDatabaseHas('pages', [
            'name' => $page->getName(),
            'content' => $page->getContent(),
        ]);

        $pageDB = Page::find($page->getId());
        $this->assertTrue($pageDB->testAttributesMatchFillable());
    }
}
