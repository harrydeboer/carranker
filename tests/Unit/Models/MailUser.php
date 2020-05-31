<?php

namespace Tests\Unit\Models;

use App\Models\Make;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MailUser extends TestCase
{
    use DatabaseMigrations;

    public function testMailUserInDB()
    {
        $mailUser = factory(MailUser::class)->create();

        $this->assertDatabaseHas('mail_users', [
            'id' => $mailUser->getId(),
            'domain' => $mailUser->getDomain(),
            'password' => $mailUser->getPassword(),
            'email' => $mailUser->getEmail(),
            'forward' => $mailUser->getForward(),
        ]);

        $mailUser = MailUser::find($mailUser->getId());
        $this->assertTrue($mailUser->testAttributesMatchFillable());
    }
}