<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Aspects;
use App\Models\FXRate;
use App\Models\Trim;
use App\Models\User;
use App\Repositories\Elastic\TrimRepository;
use Tests\FeatureTestCase;

class ModelPageTest extends FeatureTestCase
{
    private TrimRepository $trimRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->trimRepository = $this->app->make(TrimRepository::class);
    }

    public function testModelPage()
    {
        FXRate::factory()->create();
        $trimEloquent = Trim::factory()->create();
        $this->artisan('process:queue')->execute();
        $trim = $this->trimRepository->get($trimEloquent->getId());
        $response = $this->get('/model/' . $trim->getModel()->getMakeName() . '/' . $trim->getModel()->getName());

        $response->assertStatus(200);

        $response = $this->get('/model/' . $trim->getModel()->getMakeName() . '/' . $trim->getModel()->getName() .
                               '?trimId=' . $trim->getId());
        $generation = $trim->getYearBegin() . '-' . $trim->getYearEnd();
        $response->assertSee('<option value="' . $generation . '" selected>' . $generation . '</option>', false);
        $response->assertStatus(200);
    }

    public function test404()
    {
        $response = $this->get('/model/doesNotExist/doesNotExist');

        $response->assertStatus(404);
    }

    public function testRateCar()
    {
        $trimEloquent = Trim::factory()->create();
        $this->artisan('process:queue')->execute();
        $trim = $this->trimRepository->get($trimEloquent->getId());

        $user = User::factory()->create();
        $user->setAttribute('email_verified_at', date('Y-m-d h:i:s'));

        $postArrayFirst = [
            'trim-id' => (string) $trim->getId(),
            'content' => null,
            're-captcha-token' => 'notUsedInTests',
        ];
        foreach (Aspects::getAspects() as $aspect) {
            $postArrayFirst['star'][$aspect] = '10';
        }

        $response = $this->actingAs($user)->post('/rateCar/', $postArrayFirst);
        $response->assertSee('true', false);
        $response->assertStatus(200);

        $this->artisan('process:queue')->execute();

        $trimDBFirst = $this->trimRepository->get($trimEloquent->getId());

        foreach (Aspects::getAspects() as $aspect) {
            $rating = ($trim->getAspect($aspect) * $trim->getVotes() + $postArrayFirst['star'][$aspect]) /
                ($trim->getVotes() + 1);
            $this->assertEquals($rating, $trimDBFirst->getAspect($aspect));
        }

        $postArraySecond = $postArrayFirst;
        foreach (Aspects::getAspects() as $aspect) {
            $postArraySecond['star'][$aspect] = '8';
        }

        $response = $this->actingAs($user)->post('/rateCar/', $postArraySecond);
        $response->assertSee('true', false);
        $response->assertStatus(200);

        $this->artisan('process:queue')->execute();

        $trimDBSecond = $this->trimRepository->get($trimEloquent->getId());

        foreach (Aspects::getAspects() as $aspect) {
            $rating = ($trimDBFirst->getAspect($aspect) * $trimDBFirst->getVotes() + $postArraySecond['star'][$aspect] -
                    $postArrayFirst['star'][$aspect]) / $trimDBFirst->getVotes();
            $this->assertEquals($rating, $trimDBSecond->getAspect($aspect));
        }
    }
}
