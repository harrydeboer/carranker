<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Models\Rating;
use App\Repositories\UserRepository;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
    }

    public function testGetByName()
    {
        $user = User::factory()->create();
        $userFromDb = $this->userRepository->getByName($user->getUsername());

        $this->assertEquals($user->getId(), $userFromDb->getId());
    }

    public function testGetByEmail()
    {
        $user = User::factory()->create();
        $userFromDb = $this->userRepository->getByEmail($user->getEmail());

        $this->assertEquals($user->getId(), $userFromDb->getId());
    }

    public function getRatingsTrim()
    {
        $rating = Rating::factory()->create();
        $this->assertNull($this->userRepository->getRatingsTrim(null, $rating->getTrim()->getId()));

        $ratingDB = $this->userRepository->getRatingsTrim($rating->getUser(), $rating->getTrim()->getId());

        $this->assertEquals($ratingDB->getId(), $rating->getId());
        $this->assertEquals($ratingDB->getUser()->getId(), $rating->getUser()->getId());
        $this->assertEquals($ratingDB->getTrim()->getId(), $rating->getTrim()->getId());
        $this->assertEquals($ratingDB->getModel()->getId(), $rating->getModel()->getId());
        $this->assertEquals($ratingDB->getContent(), $rating->getContent());
    }

    public function getRatingsModel()
    {
        $rating = Rating::factory()->create();
        $this->assertTrue(count($this->userRepository->getRatingsModel(null, $rating->getTrim()->getId())) === 0);

        $ratingsDB = $this->userRepository->getRatingsModel($rating->getUser(), $rating->getModel()->getId());

        foreach ($ratingsDB as $ratingDB) {
            $this->assertEquals($ratingDB->getId(), $rating->getId());
            $this->assertEquals($ratingDB->getUser()->getId(), $rating->getUser()->getId());
            $this->assertEquals($ratingDB->getTrim()->getId(), $rating->getTrim()->getId());
            $this->assertEquals($ratingDB->getModel()->getId(), $rating->getModel()->getId());
            $this->assertEquals($ratingDB->getContent(), $rating->getContent());
        }
    }
}
