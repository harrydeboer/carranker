<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\NavForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NavFormTest extends TestCase
{
    use DatabaseMigrations;

    public function testNavForm()
    {
        $form = new NavForm();

        $request = request();
        $request->setMethod('GET');
        $request->query->set('query', 'Ford');

        $this->assertTrue($form->validateFull($request));
    }
}