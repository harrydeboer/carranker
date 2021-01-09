<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

use App\Models\MailUser;
use App\Repositories\MailUserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MailUserTest extends LoginAdmin
{
    private MailUserRepository $mailUserRepository;

    public function setUp(): void
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
        $email = 'test@test.com';

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
        $mailUser = MailUser::factory()->create(['email' => $oldEmail]);

        $newEmail = 'new@new.com';
        $response = $this->post(route('admin.mail.users.update'), [
            'id' => (string) $mailUser->getId(),
            'domain' => 'carranker.com',
            'email' => $newEmail,
            'forward' => null
        ]);

        $response->assertStatus(302);

        $this->mailUserRepository->getByEmail($newEmail);

        $this->expectException(\TypeError::class);

        $this->mailUserRepository->getByEmail($oldEmail);
    }

    public function testUpdatePassword()
    {
        $oldPassword = 'oldSecret';
        $email = 'test@password.com';
        $mailUser = MailUser::factory()
            ->create([
                         'password' => $oldPassword,
                         'email' => $email,
                     ]);

        $newPassword = 'newSecret';
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