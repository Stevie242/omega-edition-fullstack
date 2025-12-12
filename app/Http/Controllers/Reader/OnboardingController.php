<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function show(Request $request): Response
    {
        $profile = $request->user()
            ->readerProfile()
            ->firstOrCreate([], ['is_completed' => false]);

        return Inertia::render('reader/Onboarding', [
            'profile' => $profile,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $profile = $request->user()
            ->readerProfile()
            ->firstOrCreate([], ['is_completed' => false]);

        if ($profile->is_completed) {
            return redirect()->route('reader.dashboard');
        }

        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'avatar_url' => ['nullable', 'string', 'max:2048'],
            'birthdate' => ['required', 'date', 'before_or_equal:today'],
            'preferred_genres' => ['nullable', 'array'],
            'preferred_genres.*' => ['string', 'max:50'],
            'preferred_formats' => ['nullable', 'array'],
            'preferred_formats.*' => ['string', 'max:50'],
            'preferred_themes' => ['nullable', 'array'],
            'preferred_themes.*' => ['string', 'max:50'],
            'language_preferences' => ['nullable', 'string', 'max:255'],
        ]);

        $birthdate = new \DateTime($data['birthdate']);
        $age = $birthdate->diff(new \DateTime('now'))->y;

        if ($age < 13) {
            return back()->withErrors([
                'birthdate' => 'Tu dois avoir au moins 13 ans pour t’inscrire.',
            ]);
        }

        $profile->fill($data);
        $profile->age = $age;
        $profile->is_completed = true;
        $profile->save();

        $request->user()->update([
            'name' => trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? '')) ?: $request->user()->name,
        ]);

        return redirect()->route('reader.dashboard');
    }
}
