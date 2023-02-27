<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'test admin',
            'email' => 'admin@admin.ru',
            'role_key' => 'admin',
            'password' => Hash::make('34521234'),
        ]);

        UserRoles::updateOrCreate([
            'key' => 'admin'
        ], [
            'key' => 'admin',
            'name' => 'Admin',
            'allows' => ['all'],
        ]);
    }
}
