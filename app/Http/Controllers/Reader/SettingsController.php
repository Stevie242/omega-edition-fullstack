<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function profile(): Response
    {
        return Inertia::render('reader/settings/Profile');
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
