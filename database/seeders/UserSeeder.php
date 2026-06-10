<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy admin user for login testing
        User::updateOrCreate(
            ['Name' => 'admin'],
            [
                'Name' => 'admin',
                'Code' => 'ADM01',
                'Password' => Hash::make('password'),
                'IsActive' => 1,
            ]
        );
    }
}
