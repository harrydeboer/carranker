<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\MailUser;
use App\Repositories\MySQL\MailUserRepository;
use Tests\FeatureTestCase;

class MailUserRepositoryTest extends FeatureTestCase
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

        $this->assertCount(2, $mailUsers);
    }

    public function testFindByName()
    {
        $mailUser = MailUser::factory()->create();
        $mailUserFromDb = $this->mailUserRepository->getByEmail($mailUser->getEmail());

        $this->assertEquals($mailUser->getId(), $mailUserFromDb->getId());
    }
}
