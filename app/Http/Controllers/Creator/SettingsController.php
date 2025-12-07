<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function profile(): Response
    {
        return Inertia::render('creator/settings/Profile');
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
