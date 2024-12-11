<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Panggil seeder RolesTableSeeder terlebih dahulu
        $this->call(RolesTableSeeder::class);

        // Panggil seeder SuperAdminSeeder
        $this->call(SuperAdminSeeder::class);
    }
}
