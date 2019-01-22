<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\NavForm;
use Tests\TestCase;

class NavFormTest extends TestCase
{
    public function testNavForm()
    {
        $form = new NavForm();

        $request = request();
        $request->setMethod('GET');
        $request->query->set('query', 'Ford');

        $this->assertTrue($form->validateFull($request));
    }
}