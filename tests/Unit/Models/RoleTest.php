<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    public function testRoleNameInDB()
    {
        $role = Role::factory()->create();

        $this->assertDatabaseHas('roles', [
            'id' => $role->getId(),
            'name' => $role->getName(),
        ]);

        $roleDb = (new Role())->find($role->getId());
        $this->assertTrue($roleDb->testAttributesMatchFillable());
    }
}
