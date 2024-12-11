<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'ADMIN'],
            ['name' => 'USER'],
            ['name' => 'SELLER'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
