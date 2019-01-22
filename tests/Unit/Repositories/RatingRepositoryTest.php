<?php

declare(strict_types=1);

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
}