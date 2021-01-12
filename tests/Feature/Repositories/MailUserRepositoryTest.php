<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\MailUser;
use App\Repositories\MailUserRepository;
use Tests\TestCase;

class MailUserRepositoryTest extends TestCase
{
    private MailUserRepository $mailUserRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailUserRepository = $this->app->make(MailUserRepository::class);
    }

    public function testFindAll()
    {
        MailUser::factory()->create();
        MailUser::factory()->create();
        MailUser::factory()->create();

        $mailUsers = $this->mailUserRepository->findAll(2);

        $this->assertEquals(count($mailUsers), 2);
    }

    public function testFindByName()
    {
        $mailUser = MailUser::factory()->create();
        $mailUserFromDb = $this->mailUserRepository->getByEmail($mailUser->getEmail());

        $this->assertEquals($mailUser->getId(), $mailUserFromDb->getId());
    }
}
