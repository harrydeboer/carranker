<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

use App\Repositories\RatingRepository;
use App\Models\Rating;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReviewTest extends LoginAdmin
{
    private RatingRepository $ratingRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ratingRepository = app()->make(RatingRepository::class);
    }

    public function testView()
    {
        $response = $this->get(route('admin.reviews'));

        $response->assertStatus(200);
    }

    public function testApprove()
    {
        $review = Rating::factory()->create(['pending' => 1]);

        $response = $this->post(route('admin.reviews.approve'), [
            'id' => (string) $review->getId(),
        ]);

        $response->assertStatus(302);

        $reviewUpdated = $this->ratingRepository->get($review->getId());

        $this->assertEquals( 0, $reviewUpdated->getPending());
    }

    public function testDelete()
    {
        $review = Rating::factory()->create(['pending' => 1]);

        $response = $this->post(route('admin.reviews.delete'), [
            'id' => (string) $review->getId(),
        ]);

        $response->assertStatus(302);

        $this->expectException(ModelNotFoundException::class);

        $this->ratingRepository->get($review->getId());
    }
}