<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Tests\FeatureTestCase;

class RoleRepositoryTest extends FeatureTestCase
{
    private RoleRepository $roleRepository;

    protected function setUp(): void
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
