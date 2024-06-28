<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('Test@2024'),
            'role' => 1,
        ]);

        User::create([
            'name' => 'Mr. Admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('Test@2024'),
            'role' => 2,
        ]);

        User::create([
            'name' => 'Mr. User',
            'email' => 'user@email.com',
            'password' => bcrypt('Test@2024'),
            'role' => 3,
        ]);

        /*foreach (range('A', 'Z') as $index => $alphabet)
        {
            User::create([
                'name' => 'Mr. User ' . $alphabet,
                'email' => 'user' . Str::lower($alphabet) . '@email.com',
                'password' => bcrypt('Buyonia@12345'),
                'role' => 3,
            ]);
        }*/
    }
}
