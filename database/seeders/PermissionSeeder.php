<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::initAvailablePermissionsForSeeding();
        foreach ($permissions as $permissionType => $key) {
            foreach ($permissions[$permissionType] as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission
                ], [
                    'name' => $permission
                ]);
            }
        }
    }
}