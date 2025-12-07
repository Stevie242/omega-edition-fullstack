<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Creator\UpdateCreatorProfileRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Arr;
use App\Services\MediaService;

class SettingsController extends Controller
{
    protected function resolveUrl(?string $value, MediaService $media): ?string
    {
        if (!$value) {
            return null;
        }

        // If already an absolute URL (http/https), return as-is.
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return $media->url($value);
    }

    public function profile(MediaService $media): Response
    {
        $user = request()->user()->load('creatorProfile');

        $profile = $user->creatorProfile;
        if ($profile) {
            $profile->avatar_url = $this->resolveUrl($profile->avatar_url, $media);
            $profile->cover_url = $this->resolveUrl($profile->cover_url, $media);
        }

        return Inertia::render('creator/settings/Profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function updateProfile(UpdateCreatorProfileRequest $request, MediaService $media): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $profileData = Arr::only($data, [
            'first_name',
            'last_name',
            'display_name',
            'age',
            'gender',
            'nationality',
            'location',
            'languages',
            'headline',
            'bio',
            'signature_style',
            'favorite_formats',
            'portfolio_links',
            'moodboard',
            'website',
            'phone',
            'availability',
            'avatar_url',
            'cover_url',
        ]);

        if ($request->hasFile('avatar_file')) {
            $stored = $media->storeImage($request->file('avatar_file'), 'creators/avatars');
            $profileData['avatar_url'] = $stored; // store path
        } elseif (($profileData['avatar_url'] ?? '') === '') {
            unset($profileData['avatar_url']);
        }

        if ($request->hasFile('cover_file')) {
            $stored = $media->storeImage($request->file('cover_file'), 'creators/covers');
            $profileData['cover_url'] = $stored; // store path
        } elseif (($profileData['cover_url'] ?? '') === '') {
            unset($profileData['cover_url']);
        }

        $user->fill([
            'name' => $profileData['display_name'] ?? trim(($profileData['first_name'] ?? '') . ' ' . ($profileData['last_name'] ?? '')) ?: $user->name,
            'email' => $data['email'],
        ]);

        $user->save();

        $user->creatorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData,
        );

        return back()->with([
            'success' => 'Profil mis a jour.',
        ]);
    }

    public function appearance(): Response
    {
        return Inertia::render('creator/settings/Appearance');
    }

    public function password(): Response
    {
        return Inertia::render('creator/settings/Password');
    }

    public function twoFactor(): Response
    {
        return Inertia::render('creator/settings/TwoFactor');
    }
}
