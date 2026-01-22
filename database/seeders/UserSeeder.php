<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Ismael Gomez', 'email' => 'ismael@gmail.com', 'password' => Hash::make('123456'),  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Roberto', 'email' => 'roberto@gmail.com', 'password' => Hash::make('123456'),  'created_at' => now(), 'updated_at' => now()]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
