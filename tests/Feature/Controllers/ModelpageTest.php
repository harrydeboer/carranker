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

        $response = $this->get('/model/' . $trim->getModel()->getMakename() . '/' . $trim->getModel()->getName() . '?trimId=' . $trim->getId());
        $generation = $trim->getYearBegin() . '-' . $trim->getYearEnd();
        $response->assertSee('<option value="' . $generation . '" selected>' . $generation . '</option>', false);
        $response->assertStatus(200);
    }

    public function testRate()
    {
        $trimRepository = new TrimRepository();
        $trim = $trimRepository->get(1);
        $userRepository = new UserRepository();
        $user = $userRepository->get(1);

        $postArrayFirst = [
            'generation' => $trim->getYearBegin() . '-' . $trim->getYearEnd(),
            'serie' => $trim->getYearBegin() . '-' . $trim->getYearEnd() . ';' . $trim->getFramework(),
            'trimId' => $trim->getYearBegin() . '-' . $trim->getYearEnd() . ';' . $trim->getFramework() . ';' . $trim->getId(),
            'content' => 'Review',
            'reCaptchaToken' => 'notusedintests',
        ];
        foreach (Aspect::getAspects() as $aspect) {
            $postArrayFirst['star'][$aspect] = '10';
        }

        $response = $this->actingAs($user)->post('/model/' . $trim->getModel()->getMakename() . '/' .
            $trim->getModel()->getName(), $postArrayFirst);
        $response->assertSee('showThankYou', false);
        $response->assertStatus(200);

        $trimDBFirst = $trimRepository->get(1);

        foreach (Aspect::getAspects() as $aspect) {
            $rating = ($trim->getAspect($aspect) * $trim->getVotes() + $postArrayFirst['star'][$aspect]) /
                ($trim->getVotes() + 1);
            $this->assertEquals($rating, $trimDBFirst->getAspect($aspect));
        }

        $postArraySecond = $postArrayFirst;
        foreach (Aspect::getAspects() as $aspect) {
            $postArraySecond['star'][$aspect] = '8';
        }

        $response = $this->actingAs($user)->post('/model/' . $trim->getModel()->getMakename() . '/' .
            $trim->getModel()->getName(), $postArraySecond);
        $response->assertSee('showThankYou', false);
        $response->assertStatus(200);

        $trimDBSecond = $trimRepository->get(1);

        foreach (Aspect::getAspects() as $aspect) {
            $rating = ($trimDBFirst->getAspect($aspect) * $trimDBFirst->getVotes() + $postArraySecond['star'][$aspect] -
                    $postArrayFirst['star'][$aspect]) / $trimDBFirst->getVotes();
            $this->assertEquals($rating, $trimDBSecond->getAspect($aspect));
        }
    }
}