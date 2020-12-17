<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Interfaces\Elastic\ITrimRepository;
use App\Interfaces\IRatingRepository;
use App\Models\Aspect;
use App\Models\Trim;
use App\Models\User;
use App\Models\Rating;
use Tests\TestCase;

class RatingRepositoryTest extends TestCase
{
    private $ratingRepository;
    private $trimRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->ratingRepository = $this->app->make(IRatingRepository::class);
        $this->trimRepository = $this->app->make(ITrimRepository::class);
    }

    public function testFindRecentReviews()
    {
        $rating = Rating::factory()->create(['content' => 'notnull']);

        $reviews = $this->ratingRepository->findRecentReviews(1);

        $this->assertEquals(count($reviews), 1);

        foreach ($reviews as $review) {
            $this->assertEquals($review->getContent(), $rating->getContent());
        }
    }

    public function testCreateRating()
    {
        $user = User::factory()->create();
        $trim = Trim::factory()->create();
        $content = 'content';
        $createArray = ['content' => $content];
        foreach (Aspect::getAspects() as $aspect) {
            $createArray['star'][$aspect] = '8';
        }
        $form = new \App\Forms\RatingForm($createArray);

        $rating = $this->ratingRepository->createRating($user, $trim->getModel(), $trim, $form);

        $this->assertEquals($rating->getContent(), $content);
        $this->assertEquals($rating->getModel()->getId(), $trim->getModel()->getId());
        $this->assertEquals($rating->getTrim()->getId(), $trim->getId());
        $this->assertEquals($rating->getUser()->getId(), $user->getId());

        foreach (Aspect::getAspects() as $aspect) {
            $this->assertEquals($rating->getAspect($aspect), (int) $form->star[$aspect]);
        }
    }

    public function testUpdateRating()
    {
        $rating = Rating::factory()->create(['content' => 'content']);

        $content = 'newcontent';
        $createArray = ['content' => $content];
        foreach (Aspect::getAspects() as $aspect) {
            $createArray['star'][$aspect] = '8';
        }
        $form = new \App\Forms\RatingForm($createArray);

        $rating = $this->ratingRepository->updateRating($rating, $form);

        foreach (\App\Models\Aspect::getAspects() as $aspect) {
            $this->assertEquals((int) $form->star[$aspect], $rating->getAspect($aspect));
        }
        $this->assertEquals($form->content, $rating->getContent());
    }

    public function testGetReviews()
    {
        $trim = $this->trimRepository->get(1);
        $reviewFactory = Rating::factory()->create([
            'content' => 'notnull',
            'trim_id' => $trim->getId(),
            'model_id' => $trim->getModel()->getId(),
            ]);
        $reviews = $this->ratingRepository->getReviews($trim->getModel(), 1);

        foreach ($reviews as $review) {
            $this->assertEquals($reviewFactory->getId(), $review->getId());
        }
    }

    public function testGetNumOfReviews()
    {
        $trim = $this->trimRepository->get(1);
        $model = $trim->getModel();
        $number = $this->ratingRepository->getNumOfReviews($model);

        $this->assertEquals($number, 2);
    }
}
