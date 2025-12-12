<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'theme' => ['required', 'in:system,light,dark'],
            'currency' => ['required', 'in:XAF,EUR,USD'],
            'data' => ['nullable', 'array'],
        ]);

        $user = $request->user();

        $user->preference()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'theme' => $data['theme'],
                'currency' => $data['currency'],
                'data' => $data['data'] ?? null,
            ]
        );

        return back()->with('success', 'Préférences mises à jour.');
    }
}
