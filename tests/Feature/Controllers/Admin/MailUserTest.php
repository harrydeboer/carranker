<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

use App\Models\MailUser;
use App\Repositories\MailUserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TypeError;

class MailUserTest extends LoginAdmin
{
    private MailUserRepository $mailUserRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailUserRepository = app()->make(MailUserRepository::class);
    }

    public function testView()
    {
        $response = $this->get(route('admin.mail.users'));

        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $email = 'test@testcreate.com';

        $response = $this->post(route('admin.mail.users.create'), [
            'domain' => 'carranker.com',
            'password' => 'password',
            'email' => $email,
            'forward' => null
        ]);

        $response->assertStatus(302);

        $mailUser = $this->mailUserRepository->getByEmail($email);

        $this->assertEquals($mailUser->getEmail(), $email);
    }

    public function testUpdate()
    {
        $oldEmail = 'old@old.com';
        $newEmail = 'new@new.com';

        $mailUser = MailUser::factory()->create(['email' => $oldEmail]);

        $response = $this->post(route('admin.mail.users.update'), [
            'id' => (string) $mailUser->getId(),
            'domain' => 'carranker.com',
            'email' => $newEmail,
            'forward' => null
        ]);

        $response->assertStatus(302);

        $this->mailUserRepository->getByEmail($newEmail);

        $this->expectException(TypeError::class);

        $this->mailUserRepository->getByEmail($oldEmail);
    }

    public function testUpdatePassword()
    {
        $email = 'testtest@password.com';
        $oldPassword = 'oldSecret';
        $newPassword = 'newSecret';

        $mailUser = MailUser::factory()
            ->create([
                         'password' => $oldPassword,
                         'email' => $email,
                     ]);

        $response = $this->post(route('admin.mail.users.update.password'), [
            'id' => (string) $mailUser->getId(),
            'password' => $newPassword,
        ]);

        $response->assertStatus(302);

        $mailUserUpdated = $this->mailUserRepository->getByEmail($email);

        $this->assertNotEquals($mailUser->getPassword(), $mailUserUpdated->getPassword());
    }

    public function testDelete()
    {
        $mailUser = MailUser::factory()->create();

        $response = $this->post(route('admin.mail.users.delete'), [
           'id' => (string) $mailUser->getId(),
        ]);

        $response->assertStatus(302);

        $this->expectException(ModelNotFoundException::class);

        $this->mailUserRepository->get($mailUser->getId());
    }
}