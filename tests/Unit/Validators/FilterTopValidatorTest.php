<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Validators\FilterTopValidator;
use App\Models\Aspect;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FilterTopValidatorTest extends TestCase
{
    use DatabaseMigrations;

    public function testFilterTopForm()
    {
        $formData = ['minNumVotes' => '30'];
        foreach (Aspect::getAspects() as $aspect) {
            $formData['aspect.' . $aspect] = '3';
        }

        $validator = new FilterTopValidator($formData);

        $this->assertIsArray($validator->validate());
    }
}