<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;


class SellerUserSeeder extends Seeder
{
    public function run()
    {
        $roles = ['SELLER'];

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

        Profile::firstOrCreate(
            ['user_id' => $userSeller->id],
            [
                'name' => 'Seller Name',
                'birth_date' => null,
                'gender' => 'pria',
                'phone_number' => '000000000000'
            ]
        );
    }
}
