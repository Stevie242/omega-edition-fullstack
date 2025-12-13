<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price_xaf' => 0,
                'period_label' => 'Gratuit',
                'description' => 'Découverte : premiers chapitres gratuits.',
                'perks' => [
                    'Lecture des premiers chapitres gratuits',
                    'Accès aux recommandations de base',
                ],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Classique',
                'slug' => 'classic',
                'price_xaf' => 2500,
                'period_label' => 'Mensuel',
                'description' => 'Accès illimité au catalogue standard.',
                'perks' => [
                    'Accès illimité aux séries standard',
                    'Synchronisation multi-appareils',
                    'Mode lecture sans publicité',
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price_xaf' => 4500,
                'period_label' => 'Mensuel',
                'description' => 'Lecture premium + notifications avancées.',
                'perks' => [
                    'Accès illimité à tout le catalogue',
                    'Notifications sur chaque sortie de tes mangas favoris',
                    'Accès anticipé aux chapitres premium',
                    'Support prioritaire',
                ],
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan,
            );
        }
    }
}
