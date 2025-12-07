<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Action', 'Aventure', 'Fantastique', 'Sci-fi', 'Cyberpunk',
            'Romance', 'Comédie', 'Slice of Life', 'Drame', 'Thriller',
            'Horreur', 'Mystère', 'Enigme', 'Historique', 'Guerre',
            'Sport', 'Arts martiaux', 'Supernaturel', 'Psychologique', 'Survie',
            'Mecha', 'Steampunk', 'Isekai', 'Post-apo', 'Space opera',
            'Magie', 'Mythologie', 'Politique', 'Crime', 'Noir',
            'Musique', 'Cuisine', 'Voyage', 'School Life', 'Familial',
            'Romcom', 'LitRPG', 'E-sport', 'Streaming', 'Hacking',
            'IA', 'Crypto', 'Metaverse', 'VR', 'AR',
            'Eco-fiction', 'Climat', 'Biotech', 'Cyber-sorcery', 'Dungeon',
            'Tower climb', 'Cultivation', 'System', 'Reincarnation', 'Time loop',
            'Road trip', 'Heist', 'Noir futuriste', 'Zombie', 'Kaiju',
            'Shonen', 'Seinen', 'Josei', 'Shojo', 'Webtoon Vertical',
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
