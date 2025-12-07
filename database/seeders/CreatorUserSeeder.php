<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreatorUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'creator@omega.test'],
            [
                'name' => 'Créateur Omega',
                'password' => Hash::make('password'),
                'role' => 'creator',
                'email_verified_at' => now(),
            ],
        );
    }
}
