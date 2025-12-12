<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    protected function resolveUrl(?string $value, MediaService $media): ?string
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return $media->url($value);
    }

    public function show(Request $request, MediaService $media): Response
    {
        $profile = $request->user()
            ->readerProfile()
            ->firstOrCreate([], ['is_completed' => false]);

        if ($profile) {
            $profile->avatar_url = $this->resolveUrl($profile->avatar_url, $media);
        }

        return Inertia::render('reader/Onboarding', [
            'profile' => $profile,
        ]);
    }

    public function store(Request $request, MediaService $media): RedirectResponse
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
            'avatar_file' => ['nullable', 'image', 'max:5120'],
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

        $profileData = $data;

        if ($request->hasFile('avatar_file')) {
            $stored = $media->storeImage($request->file('avatar_file'), 'readers/avatars');
            $profileData['avatar_url'] = $stored;
            $profileData['avatar_disk_path'] = $stored;
        } elseif (($profileData['avatar_url'] ?? '') === '') {
            unset($profileData['avatar_url']);
        }

        $profile->fill($profileData);
        $profile->age = $age;
        $profile->is_completed = true;
        $profile->save();

        $request->user()->update([
            'name' => trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? '')) ?: $request->user()->name,
        ]);

        return redirect()->route('reader.dashboard');
    }
}
