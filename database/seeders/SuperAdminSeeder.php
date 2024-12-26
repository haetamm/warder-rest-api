<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $roles = ['ADMIN'];

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

        Profile::firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'name' => 'Super Admin',
                'birth_date' => null,
                'gender' => 'pria',
                'phone_number' => '000000000000'
            ]
        );
    }
}
