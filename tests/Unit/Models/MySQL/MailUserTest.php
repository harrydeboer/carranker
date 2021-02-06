<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\MySQL\MailUser;

class MailUserTest extends TestCase
{
    use DatabaseMigrations;

    public function testMailUserInDB()
    {
        $mailUser = MailUser::factory()->create();

        $this->assertDatabaseHas('mail_users', [
            'id' => $mailUser->getId(),
            'domain' => $mailUser->getDomain(),
            'password' => $mailUser->getPassword(),
            'email' => $mailUser->getEmail(),
            'forward' => $mailUser->getForward(),
        ]);

        $mailUserDb = (new MailUser())->find($mailUser->getId());
        $this->assertTrue($mailUserDb->testAttributesMatchFillable());
    }
}
