<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Seller;

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
                'phone_number' => '123456789987'
            ]
        );

        Seller::firstOrCreate(
            ['user_id' => $userSeller->id],
            [
                'shop_name' => 'Sejahtera Store',
                'shop_domain' => 'sejahtera',
                'province' => 'Kepulauan Riau',
                'regencies' => 'Kota tanjung pinang',
                'district' => 'Bukit bestari',
                'villages' => 'Tanjung unggat',
                'street_name' => 'Jl. makalo mentawai barat 43',
                'postal_code' => '76803',
            ]
        );
    }
}
