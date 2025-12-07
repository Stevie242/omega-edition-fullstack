<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReaderUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'reader@omega.test'],
            [
                'name' => 'Lecteur Omega',
                'password' => Hash::make('password'),
                'role' => 'reader',
                'email_verified_at' => now(),
            ],
        );
    }
}
