<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Tests\FeatureTestCase;

class RoleRepositoryTest extends FeatureTestCase
{
    private RoleRepositoryInterface $roleRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleRepository = $this->app->make(RoleRepositoryInterface::class);
    }

    public function testGetByName()
    {
        $role = Role::factory()->create();
        $roleFromDb = $this->roleRepository->getByName($role->getName());

        $this->assertEquals($role->getId(), $roleFromDb->getId());
    }
}
