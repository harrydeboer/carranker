<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Aspect;
use App\Models\Trim;
use App\Models\User;
use App\Models\Rating;
use App\Repositories\ProfanityRepository;
use App\Repositories\RatingRepository;
use App\Repositories\Elastic\TrimRepository;
use App\Validators\RatingValidator;
use Tests\TestCase;

class RatingRepositoryTest extends TestCase
{
    private RatingRepository $ratingRepository;
    private TrimRepository $trimRepository;
    private ProfanityRepository $profanityRepository;

    public function setUp(): void
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
        $review = Rating::factory()->create(['content' => 'notnull', 'pending' => 1]);
        $reviews = $this->ratingRepository->findPendingReviews(1);

        $this->assertEquals(count($reviews), 1);
    }

    public function testCreateRating()
    {
        $user = User::factory()->create();
        $user->setAttribute('email_verified_at', date('Y-m-d h:i:s'));
        $trim = Trim::factory()->create();

        $request = request();
        $request->setMethod('POST');

        $requestParams = [
            'trimId' => (string) $trim->getId(),
            'content' => 'dummy',
            'reCaptchaToken' => 'notUsedInTests',
        ];
        foreach (Aspect::getAspects() as $aspect) {
            $requestParams['star'][$aspect] = '8';
        }
        $request->request->add($requestParams);
        $request->setMethod('POST');
        $validator = new RatingValidator($this->profanityRepository->all());
        $data = $validator->validate($request);

        $rating = $this->ratingRepository->createRating($user, $trim->getModel(), $trim, $data, 0);

        $this->assertEquals($rating->getContent(), $requestParams['content']);
        $this->assertEquals($rating->getModel()->getId(), $trim->getModel()->getId());
        $this->assertEquals($rating->getTrim()->getId(), $trim->getId());
        $this->assertEquals($rating->getUser()->getId(), $user->getId());

        foreach (Aspect::getAspects() as $aspect) {
            $this->assertEquals($rating->getAspect($aspect), (int) $data['star'][$aspect]);
        }
    }

    public function testUpdateRating()
    {
        $rating = Rating::factory()->create(['content' => 'content']);

        $request = request();
        $request->setMethod('POST');

        $requestParams = [
            'trimId' => (string) $rating->getTrim()->getId(),
            'content' => 'dummy',
            'reCaptchaToken' => 'notUsedInTests',
        ];
        foreach (Aspect::getAspects() as $aspect) {
            $requestParams['star'][$aspect] = '8';
        }
        $request->request->add($requestParams);
        $validator = new RatingValidator($this->profanityRepository->all());

        $data = $validator->validate($request);

        $rating = $this->ratingRepository->updateRating($rating, $data, 1);

        foreach (\App\Models\Aspect::getAspects() as $aspect) {
            $this->assertEquals((int) $data['star'][$aspect], $rating->getAspect($aspect));
        }
        $this->assertEquals($data['content'], $rating->getContent());
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
