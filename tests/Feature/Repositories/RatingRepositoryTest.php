<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Aspects;
use App\Models\Trim;
use App\Models\User;
use App\Models\Rating;
use App\Repositories\ProfanityRepository;
use App\Repositories\RatingRepository;
use App\Repositories\Elastic\TrimRepository;
use Tests\TestCase;

class RatingRepositoryTest extends TestCase
{
    private RatingRepository $ratingRepository;
    private TrimRepository $trimRepository;
    private ProfanityRepository $profanityRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ratingRepository = $this->app->make(RatingRepository::class);
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $this->profanityRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testFindRecentReviews()
    {
        $rating = Rating::factory()->create(['content' => 'notnull', 'pending' => 0]);

        $reviews = $this->ratingRepository->findRecentReviews(1);

        $this->assertEquals(count($reviews), 1);

        foreach ($reviews as $review) {
            $this->assertEquals($review->getContent(), $rating->getContent());
        }
    }

    public function testFindPendingReviews()
    {
        Rating::factory()->create(['content' => 'notnull', 'pending' => 1]);
        $reviews = $this->ratingRepository->findPendingReviews(1);

        $this->assertCount(1, $reviews);
    }

    public function testCreateRating()
    {
        $user = User::factory()->create();
        $user->setAttribute('email_verified_at', date('Y-m-d h:i:s'));
        $trim = Trim::factory()->create();

        $formData = [
            'trimId' => (string) $trim->getId(),
            'content' => 'dummy',
            'reCAPTCHAToken' => 'notUsedInTests',
        ];
        foreach (Aspects::getAspects() as $aspect) {
            $formData['star'][$aspect] = '8';
        }

        $rating = $this->ratingRepository->createRating($user, $trim->getModel(), $trim, $formData, 0);

        $this->assertEquals($rating->getContent(), $formData['content']);
        $this->assertEquals($rating->getModel()->getId(), $trim->getModel()->getId());
        $this->assertEquals($rating->getTrim()->getId(), $trim->getId());
        $this->assertEquals($rating->getUser()->getId(), $user->getId());

        foreach (Aspects::getAspects() as $aspect) {
            $this->assertEquals($rating->getAspect($aspect), (int) $formData['star'][$aspect]);
        }
    }

    public function testUpdateRating()
    {
        $rating = Rating::factory()->create(['content' => 'content']);

        $formData = [
            'trimId' => (string) $rating->getTrim()->getId(),
            'content' => 'dummy',
            'reCAPTCHAToken' => 'notUsedInTests',
        ];
        foreach (Aspects::getAspects() as $aspect) {
            $formData['star'][$aspect] = '8';
        }

        $rating = $this->ratingRepository->updateRating($rating, $formData, 1);

        foreach (Aspects::getAspects() as $aspect) {
            $this->assertEquals((int) $formData['star'][$aspect], $rating->getAspect($aspect));
        }
        $this->assertEquals($formData['content'], $rating->getContent());
    }

    public function testFindEarlierByTrimAndUser()
    {
        $user = User::factory()->create();
        $trim = Trim::factory()->create();
        $ratingEarlier = Rating::factory()->create([
                                                       'trim_id' => $trim->getId(),
                                                       'model_id' => $trim->getModel()->getId(),
                                                       'user_id' => $user->getId(),
                                                       'time' => 100,
                                                       'pending' => 0,
                                                   ]);
        Rating::factory()->create([
                                      'trim_id' => $trim->getId(),
                                      'model_id' => $trim->getModel()->getId(),
                                      'user_id' => $user->getId(),
                                      'time' => 101,
                                      'pending' => 0,
                                  ]);
        $earlier = $this->ratingRepository->findEarlierByTrimAndUser($trim->getId(), $user->getId());

        $this->assertEquals($ratingEarlier->getId(), $earlier->getId());
    }

    public function testGetReviews()
    {
        $trim = $this->trimRepository->get(1);
        $reviewFromFactory = Rating::factory()->create([
                                                           'content' => 'notnull',
                                                           'trim_id' => $trim->getId(),
                                                           'model_id' => $trim->getModel()->getId(),
                                                           'pending' => 0
                                                       ]);
        $reviews = $this->ratingRepository->getReviews($trim->getModel(), 1);

        foreach ($reviews as $review) {
            $this->assertEquals($reviewFromFactory->getId(), $review->getId());
        }
    }

    public function testGetNumOfReviews()
    {
        $trim = $this->trimRepository->get(1);
        $model = $trim->getModel();
        $number = $this->ratingRepository->getNumOfReviews($model);

        $this->assertEquals($number, 1);
    }
}
