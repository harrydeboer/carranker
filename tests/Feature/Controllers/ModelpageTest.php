<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\CarSpecs;
use App\Models\Aspect;
use App\Repositories\TrimRepository;
use App\Repositories\UserRepository;
use Tests\TestCase;

class ModelpageTest extends TestCase
{
    public function testModelpage()
    {
        $trimRepository = new TrimRepository();
        $trim = $trimRepository->get(1);
        $response = $this->get('/model/' . $trim->getModel()->getMakename() . '/' . $trim->getModel()->getName());

        $response->assertStatus(200);
    }

    public function testRate()
    {
        $trimRepository = new TrimRepository();
        $trim = $trimRepository->get(1);
        $userRepository = new UserRepository();
        $user = $userRepository->get(1);

        $postArray = [
            'generation' => $trim->getYearBegin() . '-' . $trim->getYearEnd(),
            'serie' => $trim->getFramework(),
            'trimId' => $trim->getId(),
            'content' => 'Review',
            'reCaptchaToken' => 'notused',
        ];

        foreach (Aspect::getAspects() as $aspect) {
            $postArray['star'][$aspect] = '10';
        }
        $response = $this->actingAs($user)->post('/model/' . $trim->getModel()->getMakename() . '/' .
            $trim->getModel()->getName(), $postArray);

        $trimDB = $trimRepository->get(1);

        $response->assertViewHas('isThankYou', true);

        foreach (Aspect::getAspects() as $aspect) {
            $rating = ($trim->getAspect($aspect) * $trim->getVotes() + $postArray['star'][$aspect]) / ($trim->getVotes() + 1);
            $this->assertEquals($rating, $trimDB->getAspect($aspect));
        }

        $response->assertStatus(200);
    }
}