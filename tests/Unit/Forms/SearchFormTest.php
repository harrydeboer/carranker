<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\SearchForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchFormTest extends TestCase
{
    use DatabaseMigrations;

    public function testSearchForm()
    {
        $searchForm = new SearchForm();

        $request = request();
        $request->setMethod('GET');
        $request->query->set('query', 'Ford');

        $this->assertTrue($searchForm->validateFull($request));
    }
}
