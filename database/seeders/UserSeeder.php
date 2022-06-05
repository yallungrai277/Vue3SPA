<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::whereEmail('admin@gmail.com')->first()) {
            $admin = User::factory()->create([
                'name' => 'Admin user',
                'email' => 'admin@gmail.com',
                'password' => 'Password123!',
            ]);

            $admin->roles()->attach(Role::where('name', Role::ADMIN_ROLE)->value('id'));
        }

        $user = User::factory()->create([
            'password' => 'Password123!'
        ]);

        $user->roles()->attach(Role::where('name', Role::USER_ROLE)->value('id'));
    }
}