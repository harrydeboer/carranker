<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Aspect;
use App\Repositories\TrimRepository;
use App\Repositories\UserRepository;
use Tests\TestCase;

class ModelPageTest extends TestCase
{
    private TrimRepository $trimRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $this->userRepository = $this->app->make(UserRepository::class);
    }

    public function testModelpage()
    {
        $trim = $this->trimRepository->get(1);
        $response = $this->get('/model/' . $trim->getModel()->getMakename() . '/' . $trim->getModel()->getName());

        $response->assertStatus(200);

        $response = $this->get('/model/' . $trim->getModel()->getMakename() . '/' . $trim->getModel()->getName() . '?trimId=' . $trim->getId());
        $generation = $trim->getYearBegin() . '-' . $trim->getYearEnd();
        $response->assertSee('<option value="' . $generation . '" selected>' . $generation . '</option>', false);
        $response->assertStatus(200);
    }

    public function test404()
    {
        $response = $this->get('/model/doesnotexist/doesnotexist');

        $response->assertStatus(404);
    }

    public function testRatecar()
    {
        $trim = $this->trimRepository->get(1);
        $user = $this->userRepository->get(1);
        $user->setAttribute('email_verified_at', date('Y-m-d h:i:s'));

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

        $response = $this->actingAs($user)->post('/rateCar/', $postArrayFirst);
        $response->assertSee('true', false);
        $response->assertStatus(200);

        $trimDBFirst = $this->trimRepository->get(1);

        foreach (Aspect::getAspects() as $aspect) {
            $rating = ($trim->getAspect($aspect) * $trim->getVotes() + $postArrayFirst['star'][$aspect]) /
                ($trim->getVotes() + 1);
            $this->assertEquals($rating, $trimDBFirst->getAspect($aspect));
        }

        $postArraySecond = $postArrayFirst;
        foreach (Aspect::getAspects() as $aspect) {
            $postArraySecond['star'][$aspect] = '8';
        }

        $response = $this->actingAs($user)->post('/rateCar/', $postArraySecond);
        $response->assertSee('true', false);
        $response->assertStatus(200);

        $trimDBSecond = $this->trimRepository->get(1);

        foreach (Aspect::getAspects() as $aspect) {
            $rating = ($trimDBFirst->getAspect($aspect) * $trimDBFirst->getVotes() + $postArraySecond['star'][$aspect] -
                    $postArrayFirst['star'][$aspect]) / $trimDBFirst->getVotes();
            $this->assertEquals($rating, $trimDBSecond->getAspect($aspect));
        }
    }
}
