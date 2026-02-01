<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
     User::create([
    'username' => 'johnDoe',
    'name' => 'john Doe',
    'email' => 'example@gmail.com',
    'password' => Hash::make('password'),
    'role' => UserRole::ADMIN,
    ]);
    }
}
