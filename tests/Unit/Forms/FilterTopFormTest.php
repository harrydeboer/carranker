<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\FilterTopForm;
use App\Models\Aspect;
use Tests\TestCase;

class FilterTopFormTest extends TestCase
{
    public function testFilterTopForm()
    {
        $form = new FilterTopForm();

        $request = request();
        $request->setMethod('GET');
        $request->query->set('minNumVotes', '30');
        foreach (Aspect::getAspects() as $aspect) {
            $request->query->set('aspect.' . $aspect, '3');
        }

        $this->assertTrue($form->validateFull($request));
    }
}