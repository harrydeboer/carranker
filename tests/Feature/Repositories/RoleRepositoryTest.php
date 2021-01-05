<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    private RoleRepository $roleRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleRepository = $this->app->make(RoleRepository::class);
    }

    public function testGetByName()
    {
        $role = Role::factory()->create();
        $roleFromDb = $this->roleRepository->getByName($role->getName());

        $this->assertEquals($role->getId(), $roleFromDb->getId());
    }
}
