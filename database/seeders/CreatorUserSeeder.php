<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreatorUserSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::updateOrCreate(
            ['email' => 'creator@omega.test'],
            [
                'name' => 'Createur Omega',
                'password' => Hash::make('password'),
                'role' => 'creator',
                'email_verified_at' => now(),
            ],
        );

        $creator->creatorProfile()->updateOrCreate(
            ['user_id' => $creator->id],
            [
                'first_name' => 'Aurelie',
                'last_name' => 'Mavoungou',
                'display_name' => 'Atelier M Boso',
                'age' => 27,
                'gender' => 'Femme',
                'nationality' => 'Congolaise (Rep. du Congo)',
                'location' => 'Brazzaville / Pointe-Noire',
                'languages' => 'FR / Kongo / EN',
                'headline' => 'Auteur, character design, afro-futurisme brazza',
                'bio' => 'Je raconte des sagas urbaines de Brazzaville et du littoral, rythme, mystique et arcs courts.',
                'signature_style' => 'Encrage marque, motifs wax revisites, lumieres crepusculaires sur le fleuve Congo',
                'favorite_formats' => 'One-shot, feuilleton 10-14 chapitres, webtoon vertical',
                'portfolio_links' => 'https://dribbble.com/basango, https://behance.net/basango',
                'moodboard' => 'Soukous et rumba brazza, street art Poto-Poto, scenes nocturnes sous neon.',
                'website' => 'https://studiobasango.com',
                'phone' => '+242 06 234 56 78',
                'availability' => 'Ouverte projets T1, prefere collaborations courtes',
                'avatar_url' => 'https://images.unsplash.com/photo-1500336624523-d727130c3328?w=240&auto=format&fit=crop&q=80',
                'cover_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200&auto=format&fit=crop&q=80',
            ]
        );
    }
}
