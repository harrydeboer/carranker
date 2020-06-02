<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\User;
use App\Repositories\UserRepository;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private $userRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    public function testGetByName()
    {
        $user = factory(User::class)->create();
        $userFromDb = $this->userRepository->getByName($user->getUsername());

        $this->assertEquals($user->getId(), $userFromDb->getId());
    }

    public function testGetByEmail()
    {
        $user = factory(User::class)->create();
        $userFromDb = $this->userRepository->getByEmail($user->getEmail());

        $this->assertEquals($user->getId(), $userFromDb->getId());
    }

    public function getRatingsTrim()
    {
        $rating = factory(\App\Models\Rating::class)->create();
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
        $rating = factory(\App\Models\Rating::class)->create();
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