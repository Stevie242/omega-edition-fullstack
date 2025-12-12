<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\ProfileCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function show(Request $request): Response
    {
        $profile = $request->user()
            ->creatorProfile()
            ->firstOrCreate([], ['is_completed' => false]);

        return Inertia::render('creator/Onboarding', [
            'profile' => $profile,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $profile = $request->user()
            ->creatorProfile()
            ->firstOrCreate([], ['is_completed' => false]);

        if ($profile->is_completed) {
            return redirect()->route('creator.dashboard');
        }

        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'display_name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'signature_style' => ['nullable', 'string', 'max:255'],
            'favorite_formats' => ['nullable', 'string', 'max:255'],
        ]);

        $profile->fill($data);
        $profile->is_completed = true;
        $profile->save();

        $request->user()->update([
            'name' => $profile->display_name ?? $request->user()->name,
        ]);

        return redirect()->route('creator.dashboard');
    }
}
