<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@fothebys.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Create 8 clients
        for ($i = 1; $i <= 8; $i++) {
            DB::table('users')->updateOrInsert(
                ['email' => "client{$i}@fothebys.test"],
                [
                    'name' => "Client {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command->info('UserSeeder: seeded 1 admin + 8 clients.');
    }
}
