<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
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

    public function profile(MediaService $media): Response
    {
        $user = request()->user()->load('readerProfile');
        $profile = $user->readerProfile;

        if ($profile) {
            $profile->avatar_url = $this->resolveUrl($profile->avatar_url, $media);
            $profile->birthdate = optional($profile->birthdate)?->toDateString();
        }

        return Inertia::render('reader/settings/Profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function updateProfile(Request $request, MediaService $media): RedirectResponse
    {
        $user = $request->user()->load('readerProfile');

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            'preferred_genres' => ['nullable', 'array'],
            'preferred_genres.*' => ['string', 'max:50'],
            'preferred_formats' => ['nullable', 'array'],
            'preferred_formats.*' => ['string', 'max:50'],
            'preferred_themes' => ['nullable', 'array'],
            'preferred_themes.*' => ['string', 'max:50'],
            'language_preferences' => ['nullable', 'string', 'max:255'],
            'avatar_url' => ['nullable', 'string', 'max:2048'],
            'avatar_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $birthdate = isset($data['birthdate']) ? new \DateTime($data['birthdate']) : null;
        $age = $birthdate ? $birthdate->diff(new \DateTime('now'))->y : null;

        if ($age !== null && $age < 13) {
            return back()->withErrors([
                'birthdate' => 'Tu dois avoir au moins 13 ans pour continuer.',
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

        $user->fill([
            'name' => trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? '')) ?: $user->name,
            'email' => $data['email'],
        ]);
        $user->save();

        $user->readerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                ...$profileData,
                'age' => $age,
            ],
        );

        return back()->with('success', 'Profil lecteur mis à jour.');
    }

    public function appearance(): Response
    {
        $user = request()->user()->load('preference');

        return Inertia::render('reader/settings/Appearance', [
            'preference' => $user->preference,
        ]);
    }

    public function password(): Response
    {
        return Inertia::render('reader/settings/Password');
    }

    public function twoFactor(): Response
    {
        return Inertia::render('reader/settings/TwoFactor');
    }
}
