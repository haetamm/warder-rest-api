<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;


class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = Role::where('name', 'ADMIN')->first();

        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'ADMIN']);
        }

        $superAdmin = User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $superAdmin->roles()->attach($superAdminRole);
    }
}
