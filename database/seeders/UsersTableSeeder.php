<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pocketledger.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@pocketledger.test'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );
    }
}
