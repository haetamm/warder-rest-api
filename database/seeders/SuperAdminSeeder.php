<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $roles = ['ADMIN', 'USER'];

        $rolesIds = [];
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $rolesIds[] = $role->id;
        }

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['password' => bcrypt('123456')]
        );

        $superAdmin->roles()->sync($rolesIds);
    }
}
