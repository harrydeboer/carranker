<?php

declare(strict_types=1);

use App\User;
use App\Repositories\UserRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $userRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    public function testGetByName()
    {
        $user = factory(User::class)->create();
        $userFromDb = $this->userRepository->getByName($user->getName());

        $this->assertEquals($user->getId(), $userFromDb->getId());
    }
}