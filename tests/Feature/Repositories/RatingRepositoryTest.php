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
        $content = 'content';
        $createArray = ['content' => $content];
        foreach (Aspect::getAspects() as $aspect) {
            $createArray['star'][$aspect] = '8';
        }
        $form = new \App\Forms\RatingForm($this->profanityRepository, $createArray);

        $rating = $this->ratingRepository->createRating($user, $trim->getModel(), $trim, $form, 0);

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
        $form = new \App\Forms\RatingForm($this->profanityRepository, $createArray);

        $rating = $this->ratingRepository->updateRating($rating, $form, 1);

        foreach (\App\Models\Aspect::getAspects() as $aspect) {
            $this->assertEquals((int) $form->star[$aspect], $rating->getAspect($aspect));
        }
        $this->assertEquals($form->content, $rating->getContent());
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
