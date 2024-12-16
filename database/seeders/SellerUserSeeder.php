<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;


class SellerUserSeeder extends Seeder
{
    public function run()
    {
        $roles = ['USER', 'SELLER'];

        $rolesIds = [];
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $rolesIds[] = $role->id;
        }

        $userSeller = User::firstOrCreate(
            ['email' => 'seller@gmail.com'],
            ['password' => bcrypt('123456')]
        );

        $userSeller->roles()->sync($rolesIds);
    }
}
