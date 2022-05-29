<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate([
            'name' => Role::ADMIN_ROLE
        ], [
            'name' => Role::ADMIN_ROLE
        ]);

        $adminRole->permissions()->sync(Permission::pluck('id'));

        $userRole = Role::firstOrCreate([
            'name' => Role::USER_ROLE
        ], [
            'name' => Role::USER_ROLE
        ]);


        $unaAllowedUserPermission = Permission::initUserUnallowedPermissionsForSeeding();
        $permissions = Permission::query();

        foreach ($unaAllowedUserPermission as $perm) {
            $permissions->where('name', '!=', $perm);
        }
        $userRole->permissions()->sync(
            $permissions->pluck('id')
        );
    }
}