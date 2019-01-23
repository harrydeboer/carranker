<?php

declare(strict_types=1);

use App\Models\Aspect;
use App\Models\Rating;
use App\Repositories\RatingRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $ratingRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->ratingRepository = new RatingRepository();
    }

    public function testFindRecentReviews()
    {
        $rating = factory(Rating::class)->create(['content' => 'notnull']);

        $reviews = $this->ratingRepository->findRecentReviews(1);

        $this->assertEquals(count($reviews), 1);

        foreach ($reviews as $review) {
            $this->assertEquals($review->getContent(), $rating->getContent());
        }
    }

    public function testCreateRating()
    {
        $user = factory(\App\User::class)->create();
        $trim = factory(\App\Models\Trim::class)->create();
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
        $rating = factory(Rating::class)->create(['content' => 'content']);

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
}